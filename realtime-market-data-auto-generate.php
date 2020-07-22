<!--

Pull the endpoints from the public server endpoint and then populate

-->
<!-- Declare --->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Step 1 --->
<div id="table"></div>

<!-- Step 2 --->
<script>

	function tableSymbols(exchange) {
		var table = $('#table');
		table.empty().html('Loading from executium endpoint');

		var url = 'https://marketdata.executium.com/api/v2/system/symbols';
		$.ajax
		({
			type: "POST",
			url: url,
			data: 'exchange=' + exchange,
			cache: false,
			crossDomain: true,
			xhrFields: {withCredentials: true},
			timeout: 6000,
			error: function (data) {
				console.log(data);
				//
				if (typeof data.responseJSON.data.error !== 'undefined') {
					table.empty().html(data.responseJSON.data.error);
				} else {
					table.empty().html('Failed to load; Please view console.');
				}
			},
			scriptCharset: "script", dataType: "", success: function (data) {
				console.log(data);

				var h = '';


				h += `<table class="pure-table" style="font-size:0.9em;">
                <thead>


                    <tr class="ovtda">
                        <th>Symbol</th>
                        <th style="width:20%;">Executium Code</th>
                        <th>Base</th>
                        <th>Quote</th>
                        <th>Update</th>
                        <th>Bid Price</th>
                        <th>Bid Qty</th>
                        <th>Ask Price</th>
                        <th>Ask Qty</th>
                    </tr>
                </thead>
                <tbody>`;

				//
				$.each(data.data, function (i, v) {
					// We do not want to show everything
					if(rnd(1,20)==1 || i<5)
                    {
						//
						var code = exchange + '-' + v.id;
						//
						h += '<tr style="font-size:0.9em;" class="row-bids-' + code + '-1 row-asks-' + code + '-1">';
						h += '<td><img src="https://cdn.executium.com/media/brands/icons/' + v.base.toLowerCase() + '.png" class="imgcheck" style="width:13px;height:13px;margin-right:1px;" /> ' + v.symbol + '</td>';
						h += '<td style="width:20%;">' + code + '</td>';
						h += '<td>' + v.base + '</td>';
						h += '<td>' + v.quote + '</td>';
						h += '<td><span class="bids-' + code + '-ago">&hellip;</span>ms</td>';
						h += '<td class="bids-' + code + '-price">&hellip;</td>';
						h += '<td class="bids-' + code + '-qty">&hellip;</td>';
						h += '<td class="asks-' + code + '-price">&hellip;</td>';
						h += '<td class="asks-' + code + '-qty">&hellip;</td>';
						h += '</tr>';

						// Always plural. Always asks, or bids, never ask or bid
						request_orderbook_server(exchange, v.id, 'bids');
						request_orderbook_server(exchange, v.id, 'asks');
					}
				});

				h += '</tbody>';
				h += '</table>';

				table.empty().html(h);

                // Image Check
				imageCheck('.imgcheck');

			}

		});

		return true;
	}

    // Temporary function for this test so that we do not load all 600+ symbols. This would result in unfair usage and a ban for your domain. Please becareful.
	function rnd(min, max)
	{
		return Math.floor(Math.random() * (max - min + 1) + min);
	}

    // Check we have the icons on the cdn
	function imageCheck(cls) {
		$.each($(cls), function () {
			var image = $(this);
			var addImage = path => {
				image.attr("src", path)
			}
			image.on('load', function () {
				console.clear();
			}).on('error', function () {
				console.clear();
				addImage("https://cdn.executium.com/media/brands/icons/none.png");
			});
		});
	}

</script>

<!-- Step 2 --->
<script>

	// Global Varialbes
	var sockets=new Array();
	var subscription_property=new Array();
	var subscription_location=new Array();
	var monitor=[];
	var wssBASE = 'wss-public.executium.com';

	//
	function socket_connect(id,wss,out)
	{
		try
		{
			//
			console.log('ID: '+id+' | Attempting: '+wss);
			//
			sockets[id] = io(wss, {'reconnection': true,'reconnectionDelay': 500,'reconnectionDelayMax':500,'reconnectionAttempts': 100});

			//
			sockets[id].on('connect', function()
			{
				//
				console.log('ID: '+id+' | Connected: '+wss);
				//
				socket_output(id,out,'connect');
				//
				for(let i in subscription_location[id])
				{
					//
					subscribe_to_orderbook(id,i);
					console.log('Connecting to '+id+' -> '+i);
				}
			});

			//
			sockets[id].on(id,'disconnect', function()
			{
				//
				console.log('ID: '+id+' Disconnected: '+wss);
				//
				socket_output(id,out,'disconnect');

			});

			sockets[id].on('connect_error', function(e)
			{
				//
				console.log('ID: '+id+' | Error: '+wss);
				//
				socket_output(id,out,'connectionerror');

			});
		}
		catch(e)
		{
			console.log('Socket - Internal Data Issue',e);
		}
	}

	function socket_output(id,oc,rsp)
	{
		//
		switch(oc)
		{
			case 'obreq':
				manage_orderbook_request(id,rsp);
				break;
			case 'ob':
				manage_orderbook_data(id,rsp);
				break;
		}
	}

	//
	function request_orderbook_server(exchange,symbol,side)
	{
		//
		var output = side+'-'+exchange+'-'+symbol;
		//
		sockets[wssBASE].send({'req':exchange,'s':symbol,'o':output});
	}

	//
	function manage_orderbook_data(id,rsp)
	{
		//
		sockets[id].on('mvsym', function(data)
		{
			console.log('Symbol Moved Server ->'+data.n,data);
			//
			unsubscribe_from_orderbook(id,subscription_property[data.n]);
			//
			var a = data.n.split('-');

			$('.'+data.n+'-price').empty().html('');
			$('.'+data.n+'-qty').empty().html('');
			$('.'+data.n+'-ago').empty().html('');

			//
			request_orderbook_server(a[1],a[2],data.n);

		});
		//
		sockets[id].on('dp', function(data)
		{
			try
			{
				//
				var j=JSON.parse(data.d);
				var n=data.n.replace('/','-');
				var ago = new Date().getTime()-j[2];
				var side = n.split('-')[0];

				var p = parseFloat(j[0]).toFixed(8);
				if(p<1)
				{
					var price=p;

				}
				else
				{
					var price=numeral(p);
					price=price.format('0,0.00000000');
				}

				//
				var qty=numeral(j[1]);
				$('.price-'+n).empty().html( price );
				$('.qty-'+n).empty().html( qty.format('0,0.00000000') );
				$('.ago-'+n).empty().html( ago );
				$('.server-'+n).empty().html( id );


				// Only for bids in this example
				if(side=='bids') {
					if(typeof monitor[n] === 'undefined') { monitor[n]=price;}

					var transition=''; var rm=true;
					if(price < monitor[n]) { var transition='transition-down'; }
					if(price > monitor[n]) { var transition='transition-up'; }
					if(ago > 60000*5) { var transition='transition-bad'; rm=false;}

					if (transition != '') {
						$('.row-' + n).addClass(transition);

						if(rm===true) {
							setTimeout(function () {
								$('.row-' + n).removeClass(transition).removeClass('transition-bad');
							}, 110);
						}
					}

					monitor[n] = price;
				}
			}
			catch(e)
			{
				console.log('error',e);
			}
		});

	}


	function manage_orderbook_request(id,rsp)
	{
		//
		sockets[id].on('obreq', function(data)
		{
			//
			var delay=0;
			//
			if(typeof sockets[data.n] === 'undefined')
			{
				if(data.n=='notavailable')
				{
					console.warn('Issue',data);
					$('.'+data.o+'-price').empty().html( 'Not available or running - '+data.s);
					delay=-1;
				}
				else
				{
					//
					socket_connect(data.n,'https:\/\/'+data.n+':2083','ob');
					//
					delay=1000;

				}
			}

			if(delay>-1)
			{
				// timeout measure temp
				setTimeout(function()
				{
					//
					var s=data.o.split('-');
					//
					if(s[0]=='bids' || s[0]=='asks')
					{
						//
						controllerOb(data.n,data.o,s[0],data.s,1,data.o);
					}

				},delay);
			}
		});
	}

	//
	function controllerOb(id,cid,side,sym,lvl,a)
	{
		//
		var subtag=side+'/'+sym+'-'+lvl;
		//
		if(typeof subscription_property[cid] !== 'undefined')
		{
			// Unsubscribe
			unsubscribe_from_orderbook(id,subscription_property[cid]);
		}

		$('.'+a+'-price').empty().html('<span class="price-'+subtag.replace("/","-")+'"></span>');
		$('.'+a+'-qty').empty().html('<span class="qty-'+subtag.replace("/","-")+'"></span>');
		$('.'+a+'-ago').empty().html('<span class="ago-'+subtag.replace("/","-")+' ago"></span>');
		$('.'+a+'-com').empty().html('<span class="com-'+subtag.replace("/","-")+'"></span>');

		//
		if(typeof subscription_location[id] === 'undefined') { subscription_location[id]=new Array(); }
		//
		subscription_property[cid]=subtag;
		subscription_location[id][subtag]=sym;

		//
		subscribe_to_orderbook(id,subtag);
	}

	function unsubscribe_from_orderbook(id,a)
	{

		delete subscription_property[a];
		delete subscription_location[id][a];
		sockets[id].send({'unsubscribe':a});
	}

	function subscribe_to_orderbook(id,tag)
	{
		//
		sockets[id].send({'subscribe':tag});
	}


</script>

<!-- Adding seperate script section for illustration purposes. --->
<!-- Step 3 and 4 --->
<script>
	//
	socket_connect(wssBASE,'https:\/\/'+wssBASE+':2083','obreq');
	//
	tableSymbols('binance');

</script>
<!-- Step 5 --->


<!-- Optional Step -->
<script>


	setInterval(function()
	{
		$.each($('.ago'),function()
		{
			var v=parseInt($(this).html());
			if(v>0)
			{
				$(this).empty().html(parseInt(v+25));
			}

		});

	},25);


</script>
