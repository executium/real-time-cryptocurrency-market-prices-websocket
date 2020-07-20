# Real-time cryptocurrency market prices access with the executium websocket
A complete look at the available websockets and how you can use them to implement the executium market data into your own projects.

- [Introduction](#introduction)
- [Online Example](#online-example)
- [How the websocket works](#how-the-websocket-works)
- [Code breakdown](#code-breakdown)
- [Subscription](#subscription)
  - [Fair usage](./FAIR-USAGE.md)

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

## Subscription
Each domain serving is provided `fair-usage` terms. We do have additional tiers of subscription for users which use a lot more resource. You can use the executium market prices websocket within fair usage guidelines and if your project is freely available. If you are charging for access or behind any paywall you must purchase a subscription related to that.

