import { __ } from '@wordpress/i18n';
import { registerBlockStyle, unregisterBlockStyle, registerBlockCollection } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';
import Block from './class/Block';

// Init Grimlock blocks
if ( window.grimlock_blocks ) {
    const { blocks } = window.grimlock_blocks;

    if ( blocks ) {
        Object.keys( blocks ).forEach( ( id_base ) => {
            new Block( blocks[ id_base ] );
        } );
    }
}

// Register a block collection for all Grimlock blocks
registerBlockCollection( 'grimlock', { title: 'Grimlock' } );

// Register block styles
registerBlockStyle( 'core/image' , {
    name: 'cut-corner',
    label: __( 'Cut Corner', 'grimlock' ),
    isDefault: false,
} );

registerBlockStyle( 'core/image' , {
	name: 'triangle',
	label: __( 'Triangle', 'grimlock' ),
	isDefault: false,
} );

registerBlockStyle( 'core/image' , {
	name: 'diamond',
	label: __( 'Diamond', 'grimlock' ),
	isDefault: false,
} );

registerBlockStyle( 'core/image' , {
	name: 'hexagon',
	label: __( 'Hexagon', 'grimlock' ),
	isDefault: false,
} );

registerBlockStyle( 'core/image' , {
	name: 'angle',
	label: __( 'Angle', 'grimlock' ),
	isDefault: false,
} );

registerBlockStyle( 'core/image' , {
	name: 'shadow',
	label: __( 'Shadow', 'grimlock' ),
	isDefault: false,
} );

registerBlockStyle( 'core/image' , {
	name: 'parallel',
	label: __( 'Parallel', 'grimlock' ),
	isDefault: false,
} );

registerBlockStyle( 'core/button' , {
    name: 'primary',
    label: __( 'Primary', 'grimlock' ),
    isDefault: true,
} );

registerBlockStyle( 'core/button' , {
    name: 'secondary',
    label: __( 'Secondary', 'grimlock' ),
    isDefault: false,
} );

registerBlockStyle( 'core/button' , {
    name: 'link',
    label: __( 'Link', 'grimlock' ),
    isDefault: false,
} );

domReady( () => {
    unregisterBlockStyle( 'core/button', 'fill' );
    unregisterBlockStyle( 'core/button', 'outline' );
} );
