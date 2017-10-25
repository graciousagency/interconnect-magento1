The Gracious Studios Interconnect module for Magento 1 channels event data to the Gracious Interconnect webservice 
which in turn formats the data and proxies it to connected consumer services. At this point the only connected consumer
is Copernica. More consumers will be connected in the future.

Event data is automatically channeled to the web service but the module also comes with 3 console commands to 
synchronize data manually. These console commands can be run from the shell folder of this module 
(app/code/local/Gracious/Interconnect/shell):
- 'php sync_customer.php' :     Synchronizes a customer by providing the --id={customerId} parameter
- 'php sync_order.php' :        Synchronizes an order by providing the --id={orderId} parameter
- 'php sync_subscriber.php' :   Synchronizes a subscriber by providing the --id={subscriberId} parameter

**To get the module up and running:**
- Create a folder /Gracious/Interconnect under app/local.
- Install the module using composer in this folder. After installation your folder structure for this
module should look like this:
````
    /app
        /code
            /local
                /Gracious
                    /Interconnect
                        /Console
                        /controllers
                        /etc
                        /Foundation
                        /Generic
                        /Helper
                        /Http
                        /Model
                        /Observer
                        /Reflection
                        /Reporting
                        /shell
                        /Support
                        CHANGELOG.md
                        composer.json
                        README.md
````
- Now create a module config file 'Gracious_Interconnect.xml' in the app/etc folder with this content:
```xml
<?xml version="1.0"?>
<config>
    <modules>
        <Gracious_Interconnect>
            <active>true</active>
            <codePool>local</codePool>
        </Gracious_Interconnect>
    </modules>
</config>
```
- You will possibly have to clear the cache after installing the module.
- In the backend of the web shop, go to System > Configuration from the main menu and click on 'Interconnect' in the 
context menu on the left. You will be presented with a form with the following fields:
    - Base Url: enter the url for the Interconnect webservice here (Provided by Gracious Studios).
    - Prefix: enter a prefix for your application. Let's say you web shop is called 'ProShop'; your prefix could be 
    'PS' for example. 
    - Api Key: Enter your api key for the Interconnect webservice (Provided by Gracious Studios). This is required for 
    authentication.
- Now click 'Save Config' in the top right corner. The module is now configured.

In order to use this module completely you will need to have an account for the Gracious Interconnect webservice. 
Please contact info@graciousstudios.nl for more information, further integrations and other possibilities. Use 
'Interconnect' for your email subject.