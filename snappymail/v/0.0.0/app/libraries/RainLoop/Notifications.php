<?php

namespace RainLoop;

class Notifications
{
	const InvalidToken = 101;
	const AuthError = 102;
	const ConnectionError = 104;
	const DomainNotAllowed = 109;
	const AccountNotAllowed = 110;

	const AccountTwoFactorAuthRequired = 120;
	const AccountTwoFactorAuthError = 121;

	const CouldNotSaveNewPassword = 130;
	const CurrentPasswordIncorrect = 131;
	const NewPasswordShort = 132;
	const NewPasswordWeak = 133;

	const ContactsSyncError = 140;

	const CantGetMessageList = 201;
	const CantGetMessage = 202;
	const CantDeleteMessage = 203;
	const CantMoveMessage = 204;
	const CantCopyMessage = 205;

	const CantSaveMessage = 301;
	const CantSendMessage = 302;
	const InvalidRecipients = 303;

	const CantSaveFilters = 351;
	const CantGetFilters = 352;
	const CantActivateFiltersScript = 353;
	const CantDeleteFiltersScript = 354;
//	const FiltersAreNotCorrect = 355;

	const CantCreateFolder = 400;
	const CantRenameFolder = 401;
	const CantDeleteFolder = 402;
	const CantSubscribeFolder = 403;
	const CantUnsubscribeFolder = 404;
	const CantDeleteNonEmptyFolder = 405;

//	const CantSaveSettings = 501;
	const CantSavePluginSettings = 502;

	const DomainAlreadyExists = 601;

//	const CantInstallPackage = 701;
//	const CantDeletePackage = 702;
	const InvalidPluginPackage = 703;
	const UnsupportedPluginPackage = 704;

	const DemoSendMessageError = 750;
	const DemoAccountError = 751;

	const AccountAlreadyExists = 801;
	const AccountDoesNotExist = 802;

	const MailServerError = 901;
	const ClientViewError = 902;
	const InvalidInputArgument = 903;
//	const UnknownNotification = 998;
	const UnknownError = 999;

	static public function GetNotificationsMessage(int $iCode, ?\Throwable $oPrevious = null) : string
	{
		if (self::ClientViewError === $iCode && $oPrevious)
		{
			return $oPrevious->getMessage();
		}

		$oClass = new \ReflectionClass(__CLASS__);
		return (\array_search($iCode, $oClass->getConstants(), true) ?: 'UnknownNotification') . '['.$iCode.']';
	}
}
