The Gracious Interconnect module for Magento 1 channels event data to the Gracious Interconnect webservice 
which in turn formats the data and proxies it to connected consumer services. At this point the only connected consumer
is Copernica. More consumers will be connected in the future.

Event data is automatically channeled to the web service but the module also comes with 3 console commands to 
synchronize data manually. These console commands can be run from the shell folder of this module 
(app/code/local/Gracious/Interconnect/shell):
- `php gracious_interconnect_sync_customer.php` :     Synchronizes a customer by providing the --id={customerId} parameter
- `php gracious_interconnect_sync_order.php` :        Synchronizes an order by providing the --id={orderId} parameter
- `php gracious_interconnect_sync_subscriber.php` :   Synchronizes a subscriber by providing the --id={subscriberId} parameter

**To get the module up and running:**
- `composer require gracious/interconnect-magento1`
- Log out of Magento admin
- You will possibly have to clear the cache after installing the module.
- In the backend of the web shop, go to System > Configuration from the main menu and click on 'Interconnect' in the 
context menu on the left. You will be presented with a form with the following fields:
    - Base Url: enter the url for the Interconnect webservice here (Provided by Gracious Studios).
    - Prefix: enter a prefix for your application. Let's say you web shop is called 'ProShop'; your prefix could be 
    'PS' for example. 
    - Api Key: Enter your api key for the Interconnect webservice (Provided by Gracious). This is required for 
    authentication.
- Now click 'Save Config' in the top right corner. The module is now configured.

In order to use this module completely you will need to have an account for the Gracious Interconnect webservice. 
Please contact info@gracious.nl for more information, further integrations and other possibilities. Use 
'Interconnect' for your email subject.