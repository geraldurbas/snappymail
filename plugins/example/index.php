<?php

class ExamplePlugin extends \RainLoop\Plugins\AbstractPlugin
{
	const
		NAME     = 'Example',
		AUTHOR   = 'SnappyMail',
		URL      = 'https://snappymail.eu/',
		VERSION  = '0.0',
		RELEASE  = '2022-03-29',
		REQUIRED = '2.14.0',
		CATEGORY = 'General',
		LICENSE  = 'MIT',
		DESCRIPTION = '';

	public function Init() : void
	{
		$this->addHook('main.fabrica', 'MainFabrica');
/*
		$this->addCss(string $sFile, bool $bAdminScope = false);
		$this->addJs(string $sFile, bool $bAdminScope = false);
		$this->addHook(string $sHookName, string $sFunctionName);

		$this->addJsonHook(string $sActionName, string $sFunctionName);
			$this->aAdditionalJson['DoPlugin'.$sActionName] = $mCallback;
			To have your own callback on a json URI, like '/?/Json/&q[]=/0/Example'

		$this->addPartHook(string $sActionName, string $sFunctionName);
			To use your own service URI, like '/example'

		$this->addTemplate(string $sFile, bool $bAdminScope = false);
		$this->addTemplateHook(string $sName, string $sPlace, string $sLocalTemplateName, bool $bPrepend = false);
*/
	}

	/**
	 * @param mixed $mResult
	 */
	public function MainFabrica(string $sName, &$mResult)
	{
		switch ($sName) {
			case 'files':
			case 'storage':
			case 'storage-local':
			case 'settings':
			case 'settings-local':
			case 'login':
			case 'domain':
			case 'filters':
			case 'address-book':
			case 'identities':
				break;

			case 'suggestions':
				if (!\is_array($mResult)) {
					$mResult = array();
				}
				require_once __DIR__ . '/ContactsSuggestions.php';
				$mResult[] = new \Plugins\Example\ContactSuggestions();
				break;
		}
	}

/*
	public function Config() : \RainLoop\Config\Plugin
	public function Description() : string
	public function FilterAppDataPluginSection(bool $bAdmin, bool $bAuth, array &$aConfig) : void
	public function Hash() : string
	public function Manager() : \RainLoop\Plugins\Manager
	public function Name() : string
	public function Path() : string
	public function SetName(string $sName) : self
	public function SetPath(string $sPath) : self
	public function SetPluginConfig(\RainLoop\Config\Plugin $oPluginConfig) : self
	public function SetPluginManager(\RainLoop\Plugins\Manager $oPluginManager) : self
	public function SetVersion(string $sVersion) : self
	public function Supported() : string
	public function UseLangs(?bool $bLangs = null) : bool
	public function getUserSettings() : array
	public function jsonParam(string $sKey, $mDefault = null)
	public function saveUserSettings(array $aSettings) : bool

	protected function configMapping() : array
	protected function jsonResponse(string $sFunctionName, $mData)
	protected function replaceTemplate(string $sFile, bool $bAdminScope = false) : self

	$this->Manager()
	$this->Manager()->Actions()
	$this->Manager()->CreatePluginByName(string $sName) : ?\RainLoop\Plugins\AbstractPlugin
	$this->Manager()->InstalledPlugins() : array
	$this->Manager()->convertPluginFolderNameToClassName(string $sFolderName) : string
	$this->Manager()->loadPluginByName(string $sName) : ?string
	$this->Manager()->Actions() : \RainLoop\Actions
	$this->Manager()->Hash() : string
	$this->Manager()->HaveJs(bool $bAdminScope = false) : bool
	$this->Manager()->CompileCss(bool $bAdminScope = false) : string
	$this->Manager()->CompileJs(bool $bAdminScope = false) : string
	$this->Manager()->CompileTemplate(array &$aList, bool $bAdminScope = false) : void
	$this->Manager()->InitAppData(bool $bAdmin, array &$aAppData, ?\RainLoop\Model\Account $oAccount = null) : self
	$this->Manager()->AddHook(string $sHookName, $mCallbak) : self
	$this->Manager()->AddCss(string $sFile, bool $bAdminScope = false) : self
	$this->Manager()->AddJs(string $sFile, bool $bAdminScope = false) : self
	$this->Manager()->AddTemplate(string $sFile, bool $bAdminScope = false) : self
	$this->Manager()->RunHook(string $sHookName, array $aArg = array(), bool $bLogHook = true) : self
	$this->Manager()->AddAdditionalPartAction(string $sActionName, $mCallbak) : self
	$this->Manager()->RunAdditionalPart(string $sActionName, array $aParts = array()) : bool
	$this->Manager()->AddProcessTemplateAction(string $sName, string $sPlace, string $sHtml, bool $bPrepend = false) : self
	$this->Manager()->AddAdditionalJsonAction(string $sActionName, $mCallback) : self
	$this->Manager()->HasAdditionalJson(string $sActionName) : bool
	$this->Manager()->RunAdditionalJson(string $sActionName)
	$this->Manager()->JsonResponseHelper(string $sFunctionName, $mData) : array
	$this->Manager()->GetUserPluginSettings(string $sPluginName) : array
	$this->Manager()->SaveUserPluginSettings(string $sPluginName, array $aSettings) : bool
	$this->Manager()->ReadLang(string $sLang, array &$aLang) : self
	$this->Manager()->IsEnabled() : bool
	$this->Manager()->Count() : int
	$this->Manager()->SetLogger(\MailSo\Log\Logger $oLogger) : self
	$this->Manager()->WriteLog(string $sDesc, int $iType = \MailSo\Log\Enumerations\Type::INFO) : void
	$this->Manager()->WriteException(string $sDesc, int $iType = \MailSo\Log\Enumerations\Type::INFO) : void
*/
}
