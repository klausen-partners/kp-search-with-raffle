/**
 * KP Raffle Search — settings screen behaviour.
 *
 * Enqueued via wp_enqueue_script() on the plugin settings page only.
 * Data comes from the `raffleAdmin` object printed by wp_localize_script().
 */
( function ( $ ) {
	'use strict';

	var settings    = window.raffleAdmin || {};
	var i18n        = settings.i18n || {};
	var themeColors = settings.themeColors || [];

	/**
	 * Reveal / mask the Search UID field.
	 */
	function initUidToggle() {
		$( '#raffle_search_uid_toggle' ).on( 'click', function () {
			var field = document.getElementById( 'raffle_search_uid' );

			if ( ! field ) {
				return;
			}

			var masked = 'password' === field.type;

			field.type       = masked ? 'text' : 'password';
			this.textContent = masked ? i18n.hide : i18n.show;
		} );
	}

	/**
	 * Media modal for the default result image.
	 */
	function initMediaPicker() {
		var frame;

		$( '#raffle_search_default_image_upload_btn' ).on( 'click', function ( e ) {
			e.preventDefault();

			if ( frame ) {
				frame.open();
				return;
			}

			frame = wp.media( {
				title: i18n.mediaTitle,
				button: { text: i18n.mediaButton },
				multiple: false
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				var $wrap      = $( '#kp-search-with-raffle-default-image-upload' );

				$( '#raffle_search_default_image_url' ).val( attachment.url ).trigger( 'change' );
				$wrap.find( 'img' ).remove();
				$wrap.prepend(
					$( '<img>' ).addClass( 'raffle-image-preview' ).attr( { src: attachment.url, alt: '' } )
				);
			} );

			frame.open();
		} );
	}

	/**
	 * Tab navigation, with the last opened tab remembered per browser.
	 */
	function initTabs() {
		var storageKey = 'raffle_active_tab';

		function activateTab( tabId, save ) {
			$( '.raffle-tab-panel' ).removeClass( 'is-active' );
			$( '#raffle-tab-nav .nav-tab' ).removeClass( 'nav-tab-active' );
			$( '#' + tabId ).addClass( 'is-active' );
			$( '#raffle-tab-nav [data-tab="' + tabId + '"]' ).addClass( 'nav-tab-active' );
			$( '#submit' ).closest( '.submit' ).toggle( 'tab-about' !== tabId );

			if ( save ) {
				try {
					localStorage.setItem( storageKey, tabId );
				} catch ( err ) {}
			}
		}

		var stored = '';

		try {
			stored = localStorage.getItem( storageKey ) || '';
		} catch ( err ) {}

		if ( stored && $( '#' + stored ).length ) {
			activateTab( stored, false );
		}

		$( '#raffle-tab-nav .nav-tab' ).on( 'click', function ( e ) {
			e.preventDefault();
			activateTab( $( this ).data( 'tab' ), true );
		} );
	}

	/**
	 * Shared colour picker overlay used by every swatch trigger.
	 */
	function initColorOverlay() {
		var $overlay     = $( '#raffle-color-overlay' );
		var $pickerInput = $( '#raffle-overlay-picker-input' );

		if ( ! $overlay.length ) {
			return;
		}

		var currentTarget  = null;
		var currentProp    = null;
		var currentPreview = null;
		var currentDefault = null;
		var $activeTrigger = null;
		var pickerInited   = false;

		if ( themeColors.length ) {
			var $list = $( '#raffle-overlay-swatches-list' );

			$.each( themeColors, function ( i, c ) {
				$list.append(
					$( '<button>' )
						.addClass( 'raffle-overlay-swatch' )
						.attr( {
							type: 'button',
							title: c.name || c.color,
							'data-color': c.color
						} )
						.css( 'background', c.color )
				);
			} );

			$( '#raffle-overlay-swatches' ).addClass( 'is-visible' );
		}

		function initPicker() {
			if ( pickerInited ) {
				return;
			}

			$pickerInput.wpColorPicker( {
				change: function ( event, ui ) {
					applyColor( ui.color.toString() );
				},
				clear: function () {
					applyColor( '' );
				}
			} );

			pickerInited = true;
		}

		function applyColor( color ) {
			if ( ! currentTarget ) {
				return;
			}

			var display = color || currentDefault;

			$( '#' + currentTarget ).val( color );

			if ( $activeTrigger ) {
				$activeTrigger.css( 'background', display );
			}

			if ( currentPreview && currentProp ) {
				$( '#' + currentPreview ).css( currentProp, display );
			}
		}

		function positionOverlay( $trigger ) {
			var rect     = $trigger[ 0 ].getBoundingClientRect();
			var top      = rect.bottom + 6;
			var left     = rect.left;
			var overlayW = 260;
			var overlayH = 400;

			if ( left + overlayW > window.innerWidth ) {
				left = Math.max( 4, window.innerWidth - overlayW - 8 );
			}

			if ( top + overlayH > window.innerHeight ) {
				top = Math.max( 4, rect.top - overlayH - 6 );
			}

			$overlay.css( { top: top + 'px', left: left + 'px' } );
		}

		function openOverlay( $trigger ) {
			currentTarget  = $trigger.data( 'target' );
			currentProp    = $trigger.data( 'prop' );
			currentPreview = $trigger.data( 'preview' );
			currentDefault = $trigger.data( 'default' );
			$activeTrigger = $trigger;

			initPicker();
			$pickerInput.wpColorPicker( 'color', $( '#' + currentTarget ).val() || currentDefault );
			positionOverlay( $trigger );
			$overlay.addClass( 'is-open' );
		}

		$( document ).on( 'click', '.raffle-swatch-trigger', function ( e ) {
			e.stopPropagation();

			var $trigger = $( this );

			if ( $overlay.hasClass( 'is-open' ) && currentTarget === $trigger.data( 'target' ) ) {
				$overlay.removeClass( 'is-open' );
				return;
			}

			openOverlay( $trigger );
		} );

		$overlay.on( 'click', '.raffle-overlay-swatch', function ( e ) {
			e.stopPropagation();

			var color = $( this ).data( 'color' );

			$pickerInput.wpColorPicker( 'color', color );
			applyColor( color );
		} );

		$( '#raffle-overlay-close' ).on( 'click', function () {
			$overlay.removeClass( 'is-open' );
		} );

		$( '#raffle-overlay-reset' ).on( 'click', function () {
			$( '#' + currentTarget ).val( '' );
			$pickerInput.wpColorPicker( 'color', currentDefault || '' );

			if ( $activeTrigger ) {
				$activeTrigger.css( 'background', currentDefault );
			}

			if ( currentPreview && currentProp ) {
				$( '#' + currentPreview ).css( currentProp, currentDefault );
			}
		} );

		$overlay.on( 'click', function ( e ) {
			e.stopPropagation();
		} );

		$( document ).on( 'click', function () {
			$overlay.removeClass( 'is-open' );
		} );
	}

	$( function () {
		initUidToggle();
		initMediaPicker();
		initTabs();
		initColorOverlay();
	} );
} )( jQuery );
