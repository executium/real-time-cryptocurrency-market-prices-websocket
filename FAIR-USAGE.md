# Fair Usage Policy
This document pertains to the usage of executium resources. Specifically accessing `endpoints` or `websockets`.

# Executium API Fair Usage Policy
Our API is provided for private and public development purposes, we discourage embedding any API endpoints in live applications. In the event you embed directly you may face ratelimiting issues. To keep the service running at optimial speed and performance for everyone, please use the API in accordance with our fair usage policy.

## What you can do

- You can use the API to store data for redistribution purposes.
- You can use the API to automate your local workflow.
- You can build development tools under the MIT license for open-source release
- You can poll according to your rate limits.

## What you cannot do

- You cannot resell the data
- You should not send public data to the endpoints
	- Sending public traffic to endpoints will result in a distrupted service for you
- Do not call the API for every page view
- Do not embed the API keys in distributed software (mobile, repos, etc)
- Do not poll the API at anything above your set rates, this will result in an IP block

## Development deployment
While you are in development, we welcome you to call the API as much as you require (within set rates). When you deply to a live product we insist you store your own static files. Please ensure your product isn't calling our API to display live content to your own users. This can result in a ban of your application.

## Your API `key(s)` and `secret(s)`
It is your responsibility to protect your API credentials. If you are embedding your API key in a deployment script, ensure that it is a `read-only` key. This is less problematic, although not completely devoid of problems. This will add protection that no data modifying can occur. Remember, we insist that you do not ship with any API credentials in a production version of your product. For example mobile application traffic can easily be sniffed and keys extracted.

Under no circumstances should you distrbute `full_access` credentials. If we detect full access keys being utilized from multiple locations we will revoke the keys as a safety measure.

## Polling
Mentioned above in the cannot "What you cannot do" section, you should avoid polling the service too aggressively. Your account has a set `ratelimit` which can be accessed in the `meta` part of a returned payload, you should adhere to that to avoid a longer ban.

## Hard Limits
The API system has several hardlimits, you should respect the ratelimit guidance in the `meta` payload. In the event you do not respect this you will be banned for a period.

## Simultaneous requests 
You can send parallel requests, but once again, the `ratelimit` will slow you if you are hitting too fast. If you continue you will face longer bans. 

## CORS and Javascript
We currently allow Javascript requests using AJAX. We want to faciliaite ease of development. But, we still stand by the point that you should not ship the product in this manner. 

## Caching
All requests are the latest version and nothing returned is from a cache.
