# Registration Forward
[![MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/fbeuster/woltlab-packages/blob/master/LICENSE)
![Version 1.1.0](https://img.shields.io/badge/Version-1.1.0-blue.svg)

## Description
This package listens to new user registrations and sends account data to a separate platform for further processing. The user is presented with an option during the registration to disable this feature. Upon disabling, the user is presented a notice with further information about why this feature is needed.

The intent for this package is to create an identical user account on a second WoltLab based platform.

### Security disclaimer
This package sends user data (including the password) and an app secrect without further encryption to a separate platform. If this platform is on a separate server, you need to add additional security measurements like HTTPS or further data encryption.

## Feautres
- Event listeners
- Internationalization
- Package Updates
- Template listeners
- User options

### Event listeners
|Eventname|Environment|Eventclassname|
|---|---|---|
|saved|user|wcf\form\RegisterForm|

### Template listeners
|Eventname|Environment|Templatename|
|---|---|---|
|sections|user|register|

### User options
|Attribute|Value|Notes|
|---|---|---|
|name|exampleRegisterForward||
|categoryname|profile.contact||
|optiontype|boolean||
|defaultvalue|1|The feature is enabled by default.|
|required|1|An answer from the user (yes or no) is required/|
|askduringregistration|1|Option visible during registration|
|editable|6|Visible for users during registration and always for admins|


## Packing instructions
1. Convert the `files` folder into a `files.tar` archive, so that it has the following structure:
```
files.tar
└── lib
    └── system
        └── event
            └── listener
                └── ExampleRegisterListener.class.php
```
2. Convert the `templates` folder into a `template.tar` archive, so that it has the following structure:
```
templates.tar
└── __registerForwardNote.tpl
```
3. Create `package_name.tar` archive with the following structure:
```
package_name.tar
├── eventListener.xml
├── files.tar
├── language
│   ├── de.xml
│   └── en.xml
├── package.xml
├── templateListener.xml
├── templates.tar
└── userOption.xml
```