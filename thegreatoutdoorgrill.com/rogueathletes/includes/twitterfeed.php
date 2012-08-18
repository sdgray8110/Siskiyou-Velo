<script src="http://widgets.twimg.com/j/2/widget.js"></script>
				<script>
				new TWTR.Widget({
				  version: 2,
				  type: 'profile',
				  rpp: 3,
				  interval: 6000,
				  width: 314,
				  height: 300,
				  theme: {
				    shell: {
				      background: '#ffcc66',
				      color: '#006699'
				    },
				    tweets: {
				      background: '#ffcc66',
				      color: '#006699',
				      links: '#ff6600'
				    }
				  },
				  features: {
				    scrollbar: false,
				    loop: false,
				    live: false,
				    hashtags: true,
				    timestamp: true,
				    avatars: false,
				    behavior: 'all'
				  }
				}).render().setUser('rogueathletes').start();
				</script>