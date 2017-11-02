# Account Change Notification
[![MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/fbeuster/woltlab-packages/blob/master/LICENSE)
![Version 1.1.2](https://img.shields.io/badge/Version-1.1.2-blue.svg)

## Description
This package listens to changes in a user account, in specific the user's name, email, password, and whether the user started/stopped the quit process. Once a change is recognized, an email notification is sent out to the user, to inform about the change.

The intent is that users who don't recognize the changes that have been made, can now take action and contact the administrators of the plattform to resolve any issues.

## Feautres
- Event listeners
- Internationalization
- Package Updates

### Event listeners
|Eventname|Environment|Eventclassname|
|---|---|---|
|saved|user|wcf\form\AccountManagementForm|


## Packing instructions
1. Convert the `files` folder into a `files.tar` archive, so that it has the following structure:
```
files.tar
└── lib
    └── system
        └── event
            └── listener
                └── ExampleAccountManagementListener.class.php
```
2. Create `package_name.tar` archive with the following structure:
```
package_name.tar
├── files.tar
├── language
│   ├── de.xml
│   └── en.xml
├── eventListener.xml
└── package.xml
```