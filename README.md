# Real-time cryptocurrency market prices access with the executium websocket
A complete look at the available websockets and how you can use them to implement the executium market data into your own projects.

- [Introduction](#introduction)
- [Online Example](#online-example)
- [How the websocket works](#how-the-websocket-works)
- [Code breakdown](#code-breakdown)
- [Subscription for endpoint access](#subscription-for-endpoint-access)
  - [Fair usage](./FAIR-USAGE.md)
- [License](#license-related-to-code)

## Introduction
Emphasis on speed and availability

## Online Example
You can view an online example at the following address: [https://marketdata.executium.com/realtime-cryptocurrency-market-prices-websockets/](https://marketdata.executium.com/realtime-cryptocurrency-market-prices-websockets/)

## How the websocket works
Due to the volume of market pairs/symbols we carry across a wide array of exchanges, we have a method of `request-location` and `access-feed` to establish the correct websocket in which you should access.

![](https://i.imgur.com/lOPv6T7.jpg)

- `teller` provides direction of where the symbol data resides, you then use this information to connect to the symbol data socket
- `symbol-server` this is where you will connect to based on that information the `teller` provided.

It is important to note, that these are always subject to change location, as they are balanced based on an overall network demand. When a symbol is rebalanced to a new location, the `mvsym` catch provides the mechanism for you to update your location. This code is provided in the example.

## Code breakdown
This section will serve as a region to breakdown in detail how the process of connecting to the websockets works.

## Subscription for endpoint access
Each domain serving is provided `fair-usage` terms. We do have additional tiers of subscription for users which use a lot more resource. You can use the executium market prices websocket within fair usage guidelines and if your project is freely available. If you are charging for access or behind any paywall you must purchase a subscription related to that.

## License related to code

MIT License

Copyright (c) 2020 executium ltd

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

