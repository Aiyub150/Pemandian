{

    function injectCSS(selector, ruleset) {

    	// Create style element if it doesn't exist
    	let _style = null;
    	if ( !document.querySelector('style#injectedCSS') ) {

    		_style = document.createElement('style');
    		_style.type = 'text/css';
    		_style.id = 'injectedCSS';
    		document.getElementsByTagName('head')[0].appendChild(_style);

    	} else {

            _style = document.querySelector('style#injectedCSS');
        }

    	let _index = _style.sheet.cssRules.length;
    	_style.sheet.insertRule( `${selector} { ${ruleset} }`, _index );
    }

    function createRow(x, y, id) {

    	let _row = document.createElement('div')
    		_row.style.top = `${y}px`;
    		_row.style.left = `${x}px`;
    		_row.classList.add('row');
    		_row.setAttribute('id', id);

    	container.appendChild(_row);
    }

    function createCard(x, y, row, id) {

    	let _card = document.createElement('div')
    		_card.style.top = `${y}px`;
    		_card.style.left = `${x}px`;
    		_card.classList.add('card');
    		_card.setAttribute('id', id);

    	document.querySelector(row).appendChild(_card);
    }

    // Settings
    const app = {
    	width : 720,
    	height : 460,
    	card : {
    		ratio : { w : 4, h : 3},
    		blockSize : 40
    	},
        animate : {
            active : true,
            speed : 3
        }
    }

    // Init elements
    const sketch = document.querySelector('#canvas');
    	  sketch.style.width = `${app.width}px`;
    	  sketch.style.height = `${app.height}px`;

    const container = document.createElement('div');
    	  container.classList.add('container');
    	  container.id = 'container';

    	  sketch.appendChild(container);

    let cardWidth = app.card.ratio.w * app.card.blockSize;
    let cardHeight = app.card.ratio.h * app.card.blockSize;
    	injectCSS('.card', `width: ${cardWidth}px; height: ${cardHeight}px`);

    let cardsNumX = Math.ceil(app.width / (app.card.ratio.h * app.card.blockSize)) + 2;
    let cardsNumY = Math.ceil(app.height / app.card.blockSize) + 3;
    const colors = ['alpha', 'beta', 'gamma'];

    // Create Elements
    for ( let v = 0, i=0; v < cardsNumY; v++, i++ ) {

    	let _vid = `row_${v}`;
    	createRow( -(app.card.blockSize * v), (app.card.blockSize * v), _vid);

    	for ( let h = 0; h < cardsNumX; h++ ) {

                let _hid = `r${v}c${h}`;
        		createCard( (app.card.blockSize * (app.card.ratio.w-1)) * h, app.card.blockSize * h, `#${_vid}`, _hid);
        		injectCSS(`#${_hid}`, `z-index: ${cardsNumX-h}`);

                let _className = colors[(h + i%3) % colors.length];
                    document.querySelector(`#${_hid}`).classList.add(`card-${_className}`);

    	}

    	injectCSS(`#${_vid}`, `z-index: ${v};`);

        // Pyramize it... ¯\_(ツ)_/¯
        if ( i % app.card.ratio.h == app.card.ratio.h - 1 ) { cardsNumX++; }
    }

    // Animation
    function animate() { document.querySelector('#canvas').classList.add('animate'); }
    function desanimate() { document.querySelector('#canvas').classList.remove('animate'); }

        injectCSS(`@keyframes card-move`, `
            0% { transform: translateX(0) translateY(0) }
            15%, 25% { transform: translateX(0) translateY(${app.card.blockSize}px) }
            40%, 50% { transform: translateX(0) translateY(${app.card.blockSize*2}px) }
            65%, 75% { transform: translateX(${app.card.blockSize}px) translateY(${app.card.blockSize*2}px) }
            90%, 100% { transform: translateX(${app.card.blockSize}px) translateY(${app.card.blockSize*3}px) }
        `);
        injectCSS(`.animate .card`, `animation: card-move ${app.animate.speed}s infinite ease-in-out;`);

    if (app.animate.active) { animate(); }

    // Activate / deactivate onclick
    injectCSS(`.container`, `cursor: pointer;`);
    document.querySelector('.container').onclick = function() {

        if (!app.animate.active) { animate() } else { desanimate() };
            app.animate.active = !app.animate.active;
    }

}