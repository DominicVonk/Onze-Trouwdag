<?php
return [
  ".*" => [
    "GET" => [
      "/(index.php)?" => "\\App\\Controllers\\MainController->index",
      "/main/?" => "\\App\\Controllers\\MainController->main",
      "/([0-9]{4})/?" => "\\App\\Controllers\\MainController->main",
      "/photos/(day|party|booth|group)/?" => "\\App\\Controllers\\MainController->photos",
      "/admin/invitees/create/?" => "\\App\\Controllers\\AdminController->create",
      "/admin/invitees/edit/([0-9]+)/?" => "\\App\\Controllers\\AdminController->edit",
      "/admin/invitees/([0-9]+)/?" => "\\App\\Controllers\\AdminController->invitees",
      "/admin/invitees/?" => "\\App\\Controllers\\AdminController->invitees",
      "/admin/theme/?" => "\\App\\Controllers\\AdminController->theme",
      "/admin/settings/?" => "\\App\\Controllers\\AdminController->settings",
      "/admin/content/main/?" => "\\App\\Controllers\\AdminController->contentMain",
      "/admin/content/present/?" => "\\App\\Controllers\\AdminController->contentPresent",
      "/admin/content/location/?" => "\\App\\Controllers\\AdminController->contentLocation",
      "/admin/content/program/?" => "\\App\\Controllers\\AdminController->contentProgram",
      "/admin/content/address/?" => "\\App\\Controllers\\AdminController->contentAddress",
      "/admin/content/photos/?" => "\\App\\Controllers\\AdminController->contentPhotos",
      "/admin/switch/?" => "\\App\\Controllers\\AdminController->switchAccount",
      "/admin/?" => "\\App\\Controllers\\AdminController->index",
      "/admin/logout/?" => "\\App\\Controllers\\AdminController->logout",
      "/login/?" => "\\App\\Controllers\\LoginController->login",
    ],
    "POST" => [
      "/api/code/?" => "\\App\\Controllers\\ApiController->code",
      "/api/login/?" => "\\App\\Controllers\\LoginController->loggingIn",
      "/api/attendence/?" => "\\App\\Controllers\\ApiController->attendence",
      "/api/invitees/create/?" => "\\App\\Controllers\\AdminController->apiCreate",
      "/api/content/main/save/?" => "\\App\\Controllers\\AdminController->contentMainSave",
      "/api/content/location/save/?" => "\\App\\Controllers\\AdminController->contentLocationSave",
      "/api/content/address/save/?" => "\\App\\Controllers\\AdminController->contentAddressSave",
      "/api/content/program/save/?" => "\\App\\Controllers\\AdminController->contentProgramSave",
      "/api/content/photos/save/?" => "\\App\\Controllers\\AdminController->contentPhotosSave",
      "/api/content/present/save/?" => "\\App\\Controllers\\AdminController->contentPresentSave",
      "/api/theme/save/?" => "\\App\\Controllers\\AdminController->themeSave",
      "/api/settings/save/?" => "\\App\\Controllers\\AdminController->settingsSave",
      "/api/invitees/edit/([0-9]+)/?" => "\\App\\Controllers\\AdminController->apiEdit"
    ],
    "CLI" => [
      "make account ([a-z0-9_\+\.]+@[a-z0-9_\+\.]+\.[a-z0-9_\+\.]+) ([0-9]+)" => "\\App\\Controllers\\CliController->make",
      "link account ([a-z0-9_\+\.]+@[a-z0-9_\+\.]+\.[a-z0-9_\+\.]+) ([0-9]+)" => "\\App\\Controllers\\CliController->link",
      "renew ([a-z0-9_\+\.]+@[a-z0-9_\+\.]+\.[a-z0-9_\+\.]+)" => "\\App\\Controllers\\CliController->renew",
    ]
  ]
];
