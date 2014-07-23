<?php

class skill{
	public $SkillNo;
	public $SkillName;
	public $MediaType;
	public $Status;
	public $CampaignNo;
	public $OutboundSkill;
	public $SLASeconds;
	public $SLAPercent;
	public $Notes;
	public $Description;
	public $Interruptible;
	public $FromEmailEditable;
	public $FromEmailAddress;
	public $UseDispositions;
	public $RequireDispositions;
	public $DispositionTimer;
	public $QueueInitPriority;
	public $QueueAcceleration;
	public $QueueFunction;
	public $QueueMaxPriority;
	public $ActiveMinWorkTime;
	public $OverrideCallerID;
	public $CallerIDNumber;
	public $UseScreenPops;
	public $UseCustomScreenPops;
	public $CustomScreenPopApp;
	public $CampaignName;
	public $LastModified;
	public $ShortAbandonThreshold;
	public $UseShortAbandonThreshold;
	public $IncludeShortAbandons;
	public $IncludeOtherAbandons;
	public $CustomScriptID;
	public $CustomScriptName;
	public $IsDialer;
	public $EnableBlending;
}

class poc{
	public $ContactCode;
	public $ContactDescription;
	public $Status;
	public $Notes;
	public $ScriptName;
	public $DefaultSkillNo;
	public $MediaType;
	public $PhoneNumber;
	public $EmailAddress;
	public $ChatName;
	public $OutboundSkill;
	public $SLASeconds;
	public $CampaignNo;
	public $CampaignName;
	public $LastModified;
}

class campaign{
	public $CampaignNo;
	public $CampaignName;
	public $Status;
	public $Description;
	public $Notes;
	public $LastModified;
}

class agent{
	public $AgentNo;
	public $Password;
	public $TeamNo;
	public $FirstName;
	public $MiddleName;
	public $LastName;
	public $Email;
	public $Status;
	public $Notes;
	public $SecurityProfileID;
	public $TeamName;
	public $LastLogin;
	public $LastModified;
	public $CurrentState;
	public $CurrentStationId;
	public $CurrentSkillNo;
	public $CurrentSkillName;
	public $UserName;
	public $UserNameDomain;
	public $ReportTo;
	public $ReportToName;
	public $ReportToFirstName;
	public $ReportToLastName;
	public $EmailRefusalTimeout;
	public $DocumentRefusalTimeout;
	public $ChatRefusalTimeout;
	public $PhoneCallRefusalTimeout;
	public $VoiceMailRefusalTimeout;
	public $NtLoginName;
	public $CreateDate;
	public $EndDate;
	public $IsSupervisor;
}

?>