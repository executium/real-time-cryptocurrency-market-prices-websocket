# How to use ExecutiumSocket

## External dependency
Your script should include `socekt.io` as external dependency
```
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
```

## Add the dependency to your project
```
<script src="https://cdn.executium.com/media/dist/realtime-cryptocurrency-markets/executium.socket.js"></script>
``` 

## Create an ExecutiumSocket Object
This instance will help you to interact with executium socket which provides live data for ask and bids for different `exchange` and `symbol`

```
socket = new ExecutiumSocket()
```

## Connect to socket
You need to connect your front-end to sockect once only.
```
socket.socket_connect(ExecutiumSocket.wssBASE, `https:\/\/${ExecutiumSocket.wssBASE}:2083`, 'obreq');
```

## Subscribe to live data for specific `exchange` and `symbol`

To get the live updates for any specific exchange and symbol you need to make a requet to socket with your listener. This request is specific for `asks` & `bids` prices.
```
let exchnage = "bitfinex";
let symbol = 'btcusdt';

let askListener = (side, ago, price, qty, time, data) => {
    // Your code here for ask update
}

let bidListener = (side, ago, price, qty, time, data) => {
    // Your code here for bid update
}

// Request for live ask prices
socket.request_orderbook_server(exchange, symbol, ExecutiumSocket.ASKS, askListener);

// Request for live bid prices
socket.request_orderbook_server(exchange, symbol, ExecutiumSocket.BIDS, bidListener);

```

Congratulations! With this you are ready to consume the live ask and bid updates for requested `exchange` & `symbol`.

## Unsubscribe for specific `exchange` and `symbol`
To unsubscribe we have 2 options 

### 1. Unsubscribe from symbol
This will unsubscribe you from ask as well as bid
```
// Example: code='bitfinex-btcusd';
let code = `{exchnage}-{symbol}`;

socket.request_orderbook_usubscribe(code)
```

### 2. Unsubscribe for specific side (ask/bid)
If you wish to unsubscribe `ask` only or `bid` only but not both just like you have subscribed to ask and bid separately then use below code
```
// Example: code='bitfinex-btcusd';
let code = `{exchnage}-{symbol}`;

let side = ExecutiumSocket.ASKS;

// Allowed value for side are (ExecutiumSocket.ASKS, ExecutiumSocket.BIDS)
socket.request_orderbook_usubscribe(code, side)
```
