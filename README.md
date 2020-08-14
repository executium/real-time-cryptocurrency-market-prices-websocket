# Real-time cryptocurrency market prices access with the executium websocket
A complete look at the available websockets and how you can use them to implement the [executium](https://executium.com/) market data into your own projects.

![Real-time crypto market prices websocket](https://i.imgur.com/VGeP4EG.png)

- [Introduction](#introduction)
  - [CDN](#cdn)
  - [Running the service](#running-the-service)
  - [Time considerations](#time-considerations)
- [Online Example](#online-example)
- [How the websocket works](#how-the-websocket-works)
- [Code breakdown](#code-breakdown)
- [Subscription for endpoint access](#subscription-for-endpoint-access)
  - [Fair usage](./FAIR-USAGE.md)
- [User Projects](#user-projects)
- [License](#license-related-to-code)
- [PHP Cryptocurrency Websocket](https://github.com/executium/php-cryptocurrency-websocket)

## Introduction
The weboscket has an emphasis on speed and availability. The code and access is geared towards developers and traders who have a thirst for comparable data points. You could use this repo for example to build algorithms that directly lock into the executium signals marketplace.

## Online Example
You can view an online example at the following address: [https://marketdata.executium.com/realtime-cryptocurrency-market-prices-websockets/](https://marketdata.executium.com/realtime-cryptocurrency-market-prices-websockets/)

![Realtime symbols delivered via websocket](https://i.imgur.com/M6Tjsxn.jpg)

In addition, we provide a [PHP websocket access](https://github.com/executium/php-cryptocurrency-websocket) repository.

### CDN
You can access the CDN by including the following:

```
<script src="https://cdn.executium.com/media/dist/realtime-cryptocurrency-markets/min.js"></script>
```

The script relies on `jQuery`, `Numerals` and `Socket.io`, for a flawless installation include the following:
```
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
```

### Running the service
When you want to connect to the server, have the following code example execute.
```javascript

// Connect to the Public Websocket
socket_connect(wssBASE,'https:\/\/'+wssBASE+':2083','obreq');
// Request Bitfinex BTCUSD
request_orderbook_server('bitfinex', 'btcusd', 'bids');

```

Make sure you have the classes on page to handle the output.

```html
<div class="bids-bitfinex-btcusd-price">&hellip;</div>
<div class="bids-bitfinex-btcusd-qty">&hellip;</div>
<div class="bids-bitfinex-btcusd-ago ago">&hellip;</div>
```

### Time considerations
You will note in the demo that we put an emphasis on time. We provide tools to promote transparency and a test bed to make sure that you are confident with the results you are getting back. You can find the [check time offset extract here](https://github.com/executium/real-time-cryptocurrency-market-prices-websocket/blob/master/examples/check-time-offset.html).

## How the websocket works
Due to the volume of market pairs/symbols we carry across a wide array of exchanges, we have a method of `request-location` and `access-feed` to establish the correct websocket in which you should access.

![](https://i.imgur.com/lOPv6T7.jpg)

- `teller` provides direction of where the symbol data resides, you then use this information to connect to the symbol data socket which is currently the base point of `wss-socket.executium.com`.
- `symbol-server` this is where you will connect to based on that information the `teller` provides back. These are subject to change and `mvsym` will indicate if a new server has taken control as it load balances itself.

It is important to note, that these are always subject to change location, as they are balanced based on an overall network demand. When a symbol is rebalanced to a new location, the `mvsym` catch provides the mechanism for you to update your location. This code is provided in the example.

## Code breakdown
This section will serve as a region to breakdown in detail how the process of connecting to the websockets works.

## Subscription for endpoint access
Each domain serving is provided `fair-usage` terms. We do have additional tiers of subscription for users which use a lot more resource. You can use the executium market prices websocket within fair usage guidelines and if your project is freely available. If you are charging for access or behind any paywall you must purchase a subscription related to that.

## User Projects
As with all of our releases, please get in touch if you have a good usage case.

- [Crypto Websocket Plugin for Wordpress](https://wordpress.org/plugins/crypto-websocket/)
- [Loui Webers cryptocurrency market pairs websocket](https://github.com/LouiWeber/cryptocurrency-market-pairs-websocket)

## License related to code

MIT License

Copyright (c) 2020 executium ltd

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

