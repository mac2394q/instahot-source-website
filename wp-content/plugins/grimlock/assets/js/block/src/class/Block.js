import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
    Disabled,
    PanelBody,
    TextControl,
    TextareaControl,
    RadioControl,
    RangeControl,
    ToggleControl,
    SelectControl,
    DatePicker,
    BaseControl,
    Dropdown,
    Button,
    __experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import {
    InspectorControls,
    __experimentalColorGradientControl as ColorGradientControl,
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { Fragment } from '@wordpress/element';
import ImageSelector from '../component/ImageSelector';
import ColorPickerControl from '../component/ColorPickerControl';
import SelectControlWithOptGroup from '../component/SelectControlWithOptGroup';

export default class Block {
    constructor( { name, args, panels } ) {
        this.name = name;
        this.args = args;
        this.panels = panels;

        this.init();
    }

    /**
     * Initialize block
     */
    init() {
        registerBlockType( this.name, {
            ...this.args,
            edit: ( { attributes, setAttributes } ) => {
                return (
                    <>
                        <InspectorControls>

                            { Object.keys( this.panels ).map( ( panelKey ) => {
                                const panel = this.panels[ panelKey ];

                                // Bail if panel has no field
                                if ( ! panel.fields )
                                    return;

                                return (
                                    <PanelBody title={ panel.label } key={ panelKey } initialOpen={false}>

                                        { this.renderFields( panel.fields, attributes, setAttributes ) }

                                    </PanelBody>
                                );
                            } ) }

                        </InspectorControls>

                        <Disabled>
                            <ServerSideRender block={ this.name }
                                              attributes={ attributes } />
                        </Disabled>
                    </>
                );
            },
            save: () => null,
        } );
    }

    /**
     * Render block fields
     *
     * @param fields Array of fields to render
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     *
     * @return Array of react elements
     */
    renderFields( fields, attributes, setAttributes ) {
        return fields.map( ( field, key ) => {
            // Bail if field has no type
            if ( ! field[ 'type' ] )
                return;

            // Get the render function for this field type
            const renderField = this[ field[ 'type' ] + 'Field' ];

            // Bail if a render function doesn't exist for this field type
            if ( ! renderField )
                return;

            return (
                <Fragment key={ key }>{ renderField( field, attributes, setAttributes ) }</Fragment>
            );
        } );
    }

    /**
     * Render a text field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    textField( args, attributes, setAttributes ) {
        // Bail if field has no name
        if ( ! args['name'] )
            return;

        return (
            <TextControl label={ args['label'] || '' }
                         value={ attributes[ args['name'] ] || '' }
                         onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) } />
        );
    }

    /**
     * Render a date field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    dateField( args, attributes, setAttributes ) {
        // Bail if field has no name
        if ( ! args['name'] )
            return;

        return (
            <BaseControl label={ args['label'] }>
                <div>
                    <Dropdown position="bottom right"
                              renderToggle={ ( { onToggle } ) => (
                                  <>
                                      <input type="text" readOnly value={ attributes[ args['name'] ] || '' } onClick={ onToggle } />
                                      <Button style={ { marginLeft: '8px' } } isSecondary isSmall onClick={ () => setAttributes( { [ args['name'] ] : '' } ) }>
                                          { __( 'Clear', 'grimlock' ) }
                                      </Button>
                                  </>
                              ) }
                              renderContent={ () => (
                                  <DatePicker currentDate={ attributes[ args['name'] ] || null }
                                              onChange={ ( date ) => { setAttributes( { [ args['name'] ] : date.split( 'T' )[0] } ); } } />
                              ) } />
                </div>
            </BaseControl>
        );
    }

    /**
     * Render a number field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    numberField( args, attributes, setAttributes ) {
        // Bail if field has no name
        if ( ! args['name'] )
            return;

        return (
            <NumberControl label={ args['label'] || '' }
                           value={ attributes[ args['name'] ] || 0 }
                           onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) }
                           className="components-base-control" />
        );
    }

    /**
     * Render a textarea field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    textareaField( args, attributes, setAttributes ) {
        // Bail if field has no name
        if ( ! args['name'] )
            return;

        return (
            <TextareaControl label={ args['label'] || '' }
                             value={ attributes[ args['name'] ] || '' }
                             onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) } />
        );
    }

    /**
     * Render a image field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    imageField( args, attributes, setAttributes ) {
        // Bail if field has no name
        if ( ! args['name'] )
            return;

        return (
            <ImageSelector label={ args['label'] || '' }
                           value={ attributes[ args['name'] ] || 0 }
                           onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) } />
        );
    }

    /**
     * Render a toggle field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    toggleField( args, attributes, setAttributes ) {
        // Bail if field has no name
        if ( ! args['name'] )
            return;

        return (
            <ToggleControl label={ args['label'] || '' }
                           checked={ !! attributes[ args['name'] ] }
                           onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) } />
        );
    }

    /**
     * Render a select field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    selectField( args, attributes, setAttributes ) {
        // Bail if field has no name or no choice
        if ( ! args['name'] || ! args['choices'] )
            return;

        let hasSubOptions = false;
        const options = Object.keys( args['choices'] ).map( ( option ) => {
            if ( args['choices'][ option ]['subchoices'] ) {
                hasSubOptions = true;

                const subOptions = Object.keys( args['choices'][ option ]['subchoices'] ).map( ( subOption ) => {
                    return { value: subOption, label: args['choices'][ option ]['subchoices'][ subOption ] };
                } );

                return { label: args['choices'][ option ]['label'], options: subOptions };
            }

            return { value: option, label: args['choices'][ option ] };
        } );

        return hasSubOptions ? (
            <SelectControlWithOptGroup label={ args['label'] || '' }
                                       value={ attributes[ args['name'] ] || '' }
                                       onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) }
                                       optgroups={ options }
                                       multiple={ args['multiple'] } />
        ) : (
            <SelectControl label={ args['label'] || '' }
                           value={ attributes[ args['name'] ] || '' }
                           onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) }
                           options={ options }
                           multiple={ args['multiple'] } />
        );
    }

    /**
     * Render a radio image field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    radioImageField( args, attributes, setAttributes ) {
        // Bail if field has no name or no choice
        if ( ! args['name'] || ! args['choices'] )
            return;

        const options = Object.keys( args['choices'] ).map( ( option ) => {
            return { value: option, label: <img src={ args['choices'][ option ] } alt={ option } /> };
        } );

        return (
            <RadioControl label={ args['label'] || '' }
                          selected={ attributes[ args['name'] ] || '' }
                          onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) }
                          options={ options }
                          className="grimlock-radio-image" />
        );
    }

    /**
     * Render a range field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    rangeField( args, attributes, setAttributes ) {
        // Bail if field has no name or no choice
        if ( ! args['name'] )
            return;

        return (
            <RangeControl label={ args['label'] || '' }
                          value={ attributes[ args['name'] ] || 0 }
                          onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) }
                          min={ args['min'] }
                          max={ args['max'] }
                          step={ args['step'] } />
        );
    }

    /**
     * Render a color field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    colorField( args, attributes, setAttributes ) {
        // Bail if field has no name or no choice
        if ( ! args['name'] )
            return;

        return (
            <ColorPickerControl label={ args['label'] || '' }
                                value={ attributes[ args['name'] ] || '#ffffff' }
                                onChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) } />
        );
    }

    /**
     * Render a gradient field
     *
     * @param args Array of field args
     * @param attributes Object containing the block attributes
     * @param setAttributes Function used to update the block attributes
     */
    gradientField( args, attributes, setAttributes ) {
        // Bail if field has no name or no choice
        if ( ! args['name'] )
            return;

        return (
            <ColorGradientControl label={ args['label'] || '' }
                                  gradientValue={ attributes[ args['name'] ] || '' }
                                  onGradientChange={ ( value ) => setAttributes( { [ args['name'] ] : value } ) } />
        );
    }
}
