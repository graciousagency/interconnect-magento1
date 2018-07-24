**1.0.0** : 2017/10/26
- Initial release.

**1.1.0** : 2018/07/24
- Module is now installable with Composer. Moved files to Magento folders to be compatible with composer installation and added Modman configuration
- Removed newsletter controller override and added pre/post controller action hooks since the old implementation did not work well with other modules also overriding the controller.
- getcwd() for root path instead of magic constant
- Prefixed shell commands with gracious_interconnect_ to prevent overwriting.
- Fixed old references to Gracious Studios in readme.
 