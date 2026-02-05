TYPO3 CMS Extension "newsletterregistration"
============================================

This extension adds a simple and FlexForm-configurable plugin that enables the creation of Frontend Users that are used as recipients for newsletter mailings.

* Renders a registration form
* Sends an opt-in link to the given email address
* Shows confirmation page (edit) with unsubscribe button, when opt-in link has ben clicked
* Saves email address as fe_user record in a configurable sysfolder
* If an email address has already been registered, an unsubscribe link will be sent

Todo: 

* Confirmation email with unsubscribe/edit link, when opt-in link is clicked.
* Form to unsubscribe anytime

 > To provide an un-subscribe link in a newsletter sent by ext:direct_mail, a custom direct mail hook has to be created in your site package.

## Compatibility and Maintenance

This package is currently maintained for the following versions:

| TYPO3 Version       | Package Version | Branch | Maintained |
|---------------------|-----------------|--------|------------|
| TYPO3 13.4.x        | 4.x             | master | Yes        |
| TYPO3 12.4.x        | 4.x             | master | Yes         |
| TYPO3 11.5.x        | 3.x             | -      | No         |
| TYPO3 8.7.x         | 2.x             | -      | No         |
| TYPO3 6.2.x - 7.9.x | 1.x             | -      | No         |

## Contribute

Feel free to fork the extension and create pull requests for new features (that are respecting backwards compatibility).
