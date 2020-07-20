# Real-time cryptocurrency market prices access with the executium websocket
A complete look at the available websockets and how you can use them to implement the executium market data into your own projects.

- [Introduction](#introduction)
- [Online Example](#online-example)

## Introduction
Emphasis on speed and availability

## Online Example
You can view an online example at the following address: [https://marketdata.executium.com/realtime-cryptocurrency-market-prices-websockets/](https://marketdata.executium.com/realtime-cryptocurrency-market-prices-websockets/)

## Connecting to the websocket
Due to the volume of market pairs/symbols we carry across a wide array of exchanges, we have a method of `request-location` and `access-feed` to establish the correct websocket in which you should access.

![](https://i.imgur.com/lOPv6T7.jpg)

- `teller` provides direction of where the symbol data resides, you then use this information to connect to the symbol data socket
- `symbol-server` this is where you will connect to based on that information the `teller` provided.

It is important to note, that these are always subject to change location, as they are balanced based on an overall network demand. When a symbol is rebalanced to a new location, the `mvsym` catch provides the mechanism for you to update your location. This code is provided in the example.

