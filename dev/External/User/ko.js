const ko = window.ko,
	$ = jQuery,
	validTransfer = data => ('move' === data.dropEffect || 'copy' === data.dropEffect) // effectAllowed
		&& data.getData('text/x-rainloop-json');

ko.bindingHandlers.editor = {
	init: (element, fValueAccessor) => {
		let editor = null;

		const fValue = fValueAccessor(),
			HtmlEditor = require('Common/HtmlEditor').default,
			fUpdateEditorValue = () => fValue && fValue.__editor && fValue.__editor.setHtmlOrPlain(fValue()),
			fUpdateKoValue = () => fValue && fValue.__editor && fValue(fValue.__editor.getDataWithHtmlMark()),
			fOnReady = () => {
				fValue.__editor = editor;
				fUpdateEditorValue();
			};

		if (ko.isObservable(fValue) && HtmlEditor) {
			editor = new HtmlEditor(element, fUpdateKoValue, fOnReady, fUpdateKoValue);

			fValue.__fetchEditorValue = fUpdateKoValue;

			fValue.subscribe(fUpdateEditorValue);

			// ko.utils.domNodeDisposal.addDisposeCallback(element, () => {
			// });
		}
	}
};

let ttn = (element, fValueAccessor) => require('Common/Momentor').timeToNode(element, ko.unwrap(fValueAccessor()));
ko.bindingHandlers.moment = {
	init: ttn,
	update: ttn
};

ko.bindingHandlers.emailsTags = {
	init: (element, fValueAccessor, fAllBindingsAccessor) => {
		const EmailModel = require('Model/Email').default,
			$el = $(element),
			fValue = fValueAccessor(),
			fAllBindings = fAllBindingsAccessor(),
			inputDelimiters = [',', ';', '\n'];

		$el.inputosaurus({
			parseOnBlur: true,
			allowDragAndDrop: true,
			focusCallback: value => {
				if (fValue && fValue.focused) {
					fValue.focused(!!value);
				}
			},
			inputDelimiters: inputDelimiters,
			autoCompleteSource: fAllBindings.autoCompleteSource || null,
			splitHook: value => {
				const v = value.trim();
				if (v && inputDelimiters.includes(v.substr(-1))) {
					return EmailModel.splitEmailLine(value);
				}
				return null;
			},
			parseHook: input =>
				input.map(inputValue => {
					const values = EmailModel.parseEmailLine(inputValue);
					return values.length ? values : inputValue;
				}).flat(Infinity).map(
					item => (item.toLine ? [item.toLine(false), item] : [item, null])
				),
			change: event => {
				element.EmailsTagsValue = event.target.value;
				fValue(event.target.value);
			}
		});

		if (fValue && fValue.focused && fValue.focused.subscribe) {
			fValue.focused.subscribe((value) => {
				$el.inputosaurus(value ? 'focus' : 'blur');
			});
		}
	},
	update: (element, fValueAccessor) => {
		const value = ko.unwrap(fValueAccessor());

		if (element.EmailsTagsValue !== value) {
			element.value = value;
			element.EmailsTagsValue = value;
			$(element).inputosaurus('refresh');
		}
	}
};

ko.bindingHandlers.draggable = {
	init: (element, fValueAccessor) => {
		if (!rl.settings.app('mobile')) {
			element.addEventListener("dragstart", e => fValueAccessor()(e));
		}
	}
};

ko.bindingHandlers.droppable = {
	init: (element, fValueAccessor) => {
		if (!rl.settings.app('mobile')) {
			const FolderList = fValueAccessor(),
				folder = ko.dataFor(element),
				fnHover = e => {
					if (validTransfer(e.dataTransfer)) {
						e.preventDefault();
						element.classList.add('droppableHover');
						FolderList.messagesDropOver(folder);
					}
				};
			element.addEventListener("dragenter", fnHover);
			element.addEventListener("dragover", fnHover);
			element.addEventListener("dragleave", e => {
				e.preventDefault();
				element.classList.remove('droppableHover');
				FolderList.messagesDropOut();
			});
			element.addEventListener("drop", e => {
				const data = JSON.parse(validTransfer(e.dataTransfer));
				if (data) {
					data.copy = data.copy && event.ctrlKey;
					e.preventDefault();
					FolderList.messagesDrop(folder, data);
				}
			});
		}
	}
};

ko.bindingHandlers.link = {
	update: (element, fValueAccessor) => element.href = ko.unwrap(fValueAccessor())
};

ko.bindingHandlers.initDom = {
	init: (element, fValueAccessor) => fValueAccessor()(element)
};

ko.bindingHandlers.onEsc = {
	init: (element, fValueAccessor, fAllBindingsAccessor, viewModel) => {
		let fn = event => {
			if ('Escape' == event.key) {
				element.dispatchEvent(new Event('change'));
				fValueAccessor().call(viewModel);
			}
		};
		element.addEventListener('keyup', fn);
		ko.utils.domNodeDisposal.addDisposeCallback(element, () => element.removeEventListener('keyup', fn));
	}
};

// extenders

ko.extenders.specialThrottle = (target, timeout) => {
	timeout = parseInt(timeout, 10);
	if (0 < timeout) {
		let timer = 0,
			valueForRead = ko.observable(!!target()).extend({ throttle: 10 });

		return ko.computed({
			read: valueForRead,
			write: (bValue) => {
				if (bValue) {
					valueForRead(bValue);
				} else if (valueForRead()) {
					clearTimeout(timer);
					timer = setTimeout(() => {
						valueForRead(false);
						timer = 0;
					}, timeout);
				} else {
					valueForRead(bValue);
				}
			}
		});
	}

	return target;
};

// functions

ko.observable.fn.validateEmail = function() {
	this.hasError = ko.observable(false);

	this.subscribe(value => this.hasError(value && !/^[^@\s]+@[^@\s]+$/.test(value)));

	this.valueHasMutated();
	return this;
};

export default ko;
