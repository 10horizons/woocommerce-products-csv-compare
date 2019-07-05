# Missing Woocommerce Product Checker

Compare Woocommerce products in your WordPress site against products in CSV file, and see if there are missing products in your Woocommerce store. 

When importing large CSV files into Woocommerce for a client, and kept getting the "some products were not imported" error, I got confused with what I have imported and what I haven't. To go through the products one by one would be a pain. So I wrote this script and decided to upload it to Github in case it could be useful to someone else too. 

### Installation

Nothing fancy. The usual standard WordPress plugin installation.
Upload plugin folder to the `/wp-content/plugins/` directory, or go to `Dashboard>Plugins>Add New` to upload the .zip file.

### A few things to note

- The plugin assumes the CSV file you're comparing against has a header or column names (as it should!).
- You should apply additional security measures like nonce etc. to improve security, as it is admittedly, not the focus when I wrote this as I was developing on a local machine. 
