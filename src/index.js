import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	__
} from '@wordpress/i18n';
import {
	PanelBody,
	TextControl,
	SelectControl
} from '@wordpress/components';

registerBlockType('wpecounter/most-viewed-block', {
	title: __('Most Popular', 'wpecounter'),
	description: __('A dynamic block showing the most popular posts.', 'wpecounter'),
	icon: 'chart-bar',
	category: 'wpecounter',
	attributes: {
		title: {
			type: 'string',
			default: __('Most Popular', 'wpecounter'),
		},
		postType: {
			type: 'string',
			default: 'post',
		},
		limit: {
			type: 'number',
			default: 5,
		},
		order: {
			type: 'string',
			default: 'DESC',
		},
	},
	example: {
		attributes: {
			title: __('Top Articles', 'wpecounter'),
			postType: 'post',
			limit: 3,
			order: 'DESC'
		},
	},

	edit: (props) => {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps();

		// Opciones que llegan desde PHP (asegúrate que wpecounterData.postTypes esté bien definido)
		const postTypeOptions = window.wpecounterData?.postTypes || [
			{ label: 'Post', value: 'post' },
		];

		return (
			<div {...blockProps}>
				<InspectorControls>
					<PanelBody title={__('Block Settings', 'wpecounter')}>
						<TextControl
							label={__('Block Title', 'wpecounter')}
							value={attributes.title}
							onChange={(value) => setAttributes({ title: value })}
							__next40pxDefaultSize={true}
							__nextHasNoMarginBottom={true}
						/>

						<SelectControl
							label={__('Post Type', 'wpecounter')}
							value={attributes.postType}
							options={postTypeOptions}
							onChange={(value) => setAttributes({ postType: value })}
							__next40pxDefaultSize={true}
							__nextHasNoMarginBottom={true}
						/>

						<TextControl
							type="number"
							label={__('Number of Posts', 'wpecounter')}
							value={attributes.limit}
							onChange={(value) => setAttributes({ limit: parseInt(value) || 1 })}
							min={1}
							__next40pxDefaultSize={true}
							__nextHasNoMarginBottom={true}
						/>

						<SelectControl
							label={__('Order', 'wpecounter')}
							value={attributes.order}
							options={[
								{ label: __('Descending', 'wpecounter'), value: 'DESC' },
								{ label: __('Ascending', 'wpecounter'), value: 'ASC' },
							]}
							onChange={(value) => setAttributes({ order: value })}
							__next40pxDefaultSize={true}
							__nextHasNoMarginBottom={true}
						/>
					</PanelBody>
				</InspectorControls>

				<h3>{attributes.title}</h3>
				<ul style={{ listStyle: 'disc', paddingLeft: '20px' }}>
					<li>{__('Post 1 1.2K', 'wpecounter')}</li>
					<li>{__('Post 2 980', 'wpecounter')}</li>
					<li>{__('Post 3 860', 'wpecounter')}</li>
				</ul>
				<small style={{ color: '#888' }}>{__('Dynamic preview not available in editor.', 'wpecounter')}</small>
			</div>
		);
	},

	save: () => null,
});