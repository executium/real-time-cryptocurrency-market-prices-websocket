class ExecutiumSocket {

    static wssBASE = 'wss-public.executium.com';
    static ASKS = 'asks';
    static BIDS = 'bids';

    constructor() {
        this.listeners = {};
        this.sockets = new Array();
        this.subscription_property = new Array();
        this.subscription_location = new Array();
    }

    socket_connect(id, wss, out) {
        let instance = this;

        try {
            instance.sockets[id] = io(wss, { 'reconnection': true, 'reconnectionDelay': 500, 'reconnectionDelayMax': 500, 'reconnectionAttempts': 100 });

            // Do this on socket connect
            instance.sockets[id].on('connect', function () {
                instance.socket_output(id, out, 'connect');

                for (let i in instance.subscription_location[id]) {
                    instance.subscribe_to_orderbook(id, i);
                }
            });


            // Do this on socket disconnect
            instance.sockets[id].on(id, 'disconnect', function () {
                instance.socket_output(id, out, 'disconnect');
            });


            // Do this on socket connection error
            instance.sockets[id].on('connect_error', function (e) {
                instance.socket_output(id, out, 'connectionerror');

            });
        }
        catch (e) {
            console.log('Socket - Internal Data Issue', e);
        }
    }

    socket_output(id, oc, rsp) {
        let instance = this;

        switch (oc) {
            case 'obreq':
                instance.manage_orderbook_request(id, rsp);
                break;
            case 'ob':
                instance.manage_orderbook_data(id, rsp);
                break;
        }
    }

    // Making a request to server for the new data
    request_orderbook_server(exchange, symbol, side, callback) {
        let instance = this;

        var output = side + '-' + exchange + '-' + symbol;

        if (callback) {
            instance.listeners[output + '-1'] = callback;
        }

        instance.sockets[ExecutiumSocket.wssBASE].send({ 'req': exchange, 's': symbol, 'o': output });
    }

    manage_orderbook_data(id, rsp) {
        let instance = this;

        // This is the case when a symbol servver is moved,
        // We need to unsubscribe from old and start listen to new one again
        instance.sockets[id].on('mvsym', function (data) {

            instance.unsubscribe_from_orderbook(id, instance.subscription_property[data.n], true);

            instance.request_orderbook_server(a[1], a[2], a[0]);

        });

        // This is the function which receives the data from socket 
        instance.sockets[id].on('dp', function (data) {
            try {

                var j = JSON.parse(data.d);
                var n = data.n.replace('/', '-');
                var ago = new Date().getTime() - j[2];

                var side = n.split('-')[0];

                var price = parseFloat(j[0]).toFixed(8);
                var qty = numeral(j[1]);

                if (instance.listeners[n]) {
                    instance.listeners[n](side, ago, price, qty, j[2], data);
                }
            }
            catch (e) {
                console.log('error', e);
            }
        });

    }

    manage_orderbook_request(id, rsp) {
        let instance = this;

        instance.sockets[id].on('obreq', function (data) {

            var delay = 0;

            if (typeof instance.sockets[data.n] === 'undefined') {
                if (data.n == 'notavailable') {
                    console.warn('Issue', data);
                    delay = -1;
                }
                else {

                    instance.socket_connect(data.n, 'https:\/\/' + data.n + ':2083', 'ob');

                    delay = 1000;
                }
            }

            if (delay > -1) {
                // timeout measure temp
                setTimeout(() => {

                    var s = data.o.split('-');

                    if (s[0] == 'bids' || s[0] == 'asks') {

                        instance.controllerOb(data.n, data.o, s[0], data.s, 1, data.o);
                    }

                }, delay);
            }
        });
    }

    controllerOb(id, cid, side, sym, lvl, a) {
        let instance = this;

        var subtag = side + '/' + sym + '-' + lvl;

        if (typeof instance.subscription_property[cid] !== 'undefined') {
            instance.unsubscribe_from_orderbook(id, instance.subscription_property[cid], true);
        }

        if (typeof instance.subscription_location[id] === 'undefined') { instance.subscription_location[id] = new Array(); }

        instance.subscription_property[cid] = subtag;
        instance.subscription_location[id][subtag] = sym;


        instance.subscribe_to_orderbook(id, subtag);
    }

    unsubscribe_from_orderbook(id, a, isInternal=false) {
        let instance = this;

        if (typeof instance.subscription_location[id] != 'undefined') {
            delete instance.subscription_property[a];
            if (typeof instance.subscription_location[id][a] != 'undefined') { delete instance.subscription_location[id][a]; }

            // Remove the listener as well
            if (!isInternal) {
                delete instance.listeners[a.replace('/', '-')];
            }
        }
        instance.sockets[id].send({ 'unsubscribe': a });
    }

    subscribe_to_orderbook(id, tag) {
        let instance = this;

        instance.sockets[id].send({ 'subscribe': tag });
    }

    // This will usubscribe you from ask as well as bids
    request_orderbook_usubscribe(code, side=null) {
        let instance = this;
        if (side && (side !== ExecutiumSocket.ASKS || side !== ExecutiumSocket.BIDS)) {
            console.error(`${side} is not a valid choice for side. Must be one from (${ExecutiumSocket.ASKS}, ${ExecutiumSocket.BIDS})`);
            return
        }

        // Example: code='bitfinex-btcusd';
        for(let serverId in instance.subscription_location) {
            for(let key in instance.subscription_location[serverId]) {
                if(code == instance.subscription_location[serverId][key]) {
                    if (side) {
                        instance.unsubscribe_from_orderbook(serverId, `${side}/${code}-1`);
                    }
                    else {
                        instance.unsubscribe_from_orderbook(serverId, `bids/${code}-1`);
                        instance.unsubscribe_from_orderbook(serverId, `asks/${code}-1`);
                    }
                    return;
                }
            }
        }
    }

}
