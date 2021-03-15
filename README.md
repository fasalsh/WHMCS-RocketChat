# WHMCS-RocketChat

Send WHCMS notifications to RocketChat
Forked from https://github.com/sapinet/WHMCS-RocketChat which only allows 1 webhook (to a single RC channel) for all notifications. This one intend to create notifications to separate channels (with multiple hooks) for each events in WHMCS.

## Installation (will correct it later)

 1. Place the RocketChat folder in your WHMCS installation (modules/notification/RocketChat)
 2. Create WebHook (Admin settings -> Integrations -> New integration -> Incoming WebHook)
 3. Paste the WebHook URL in WHMCS notifications settings
 4. Chose a username for the bot, and set it in WHMCS notifications settings
 5. You should receive a message "Connected with WHMCS", otherwise, check your WebHook URL.

```
