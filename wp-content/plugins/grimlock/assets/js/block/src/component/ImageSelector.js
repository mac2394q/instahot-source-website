import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { BaseControl, Button, ResponsiveWrapper, Spinner } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';

const ALLOWED_MEDIA_TYPES = [ 'image' ];
const UNAUTHORIZED = <p>{ __( 'To edit the background image, you need permission to upload media.', 'grimlock' ) }</p>;

class ImageSelector extends Component {
    render() {
        const { label, value, onChange, image } = this.props;

        return (
            <BaseControl label={ label } className="grimlock-image-selector">

                <MediaUploadCheck fallback={ UNAUTHORIZED }>
                    <MediaUpload onSelect={ ( image ) => onChange( image.id ) }
                                 allowedTypes={ ALLOWED_MEDIA_TYPES }
                                 value={ value }
                                 render={ ( { open } ) => (
                                     <>
                                         <Button className={ ! value ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
                                                 onClick={ open }>

                                             { ! value && ( __( 'No image selected', 'grimlock' ) ) }
                                             { !! value && ! image && <Spinner /> }
                                             { !! value && image &&
                                             <ResponsiveWrapper naturalWidth={ image.media_details.width }
                                                                naturalHeight={ image.media_details.height }>
                                                 <img src={ image.source_url } alt={ label } />
                                             </ResponsiveWrapper> }

                                         </Button>

                                         <Button style={ { margin: '10px 10px 0 0' } } onClick={ open } isSecondary>
                                             { ! value ? __( 'Select Image', 'grimlock' ) : __( 'Change Image', 'grimlock' ) }
                                         </Button>

                                         { !! value &&
                                         <Button onClick={ () => onChange( 0 ) } isLink isDestructive>
                                             { __( 'Remove image', 'grimlock' ) }
                                         </Button> }
                                     </>
                                 ) } />
                </MediaUploadCheck>

            </BaseControl>
        );
    }
}

export default compose(
    withSelect( ( select, props ) => {
        const { getMedia } = select( 'core' );
        const { value } = props;

        return {
            image: value ? getMedia( value ) : null,
        };
    } ),
)( ImageSelector );