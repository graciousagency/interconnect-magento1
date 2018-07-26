**1.0.0** : 2017/10/26
- Initial release.

**2.0.0** : 2018/07/24
- Module is now installable with Composer. Moved files to Magento folders to be compatible with composer installation and added Modman configuration
- Removed newsletter controller override and added pre/post controller action hooks since the old implementation did not work well with other modules also overriding the controller.
- getcwd() for root path instead of magic constant
- Prefixed shell commands with gracious_interconnect_ to prevent overwriting.
- Fixed old references to Gracious Studios in readme.

**2.0.1** : 2018/07/25
- Fixed admin not working. Changed translation module in config/adminhtml/system.xml because the helper alias was changed
- Removed router override in config.xml
 