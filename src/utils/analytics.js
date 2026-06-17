/**
 * Raffle analytics → Google Tag Manager.
 *
 * Because this plugin talks to the Raffle REST API directly instead of using
 * Raffle's native widget, the widget's built-in GTM events are not emitted.
 * This helper re-creates the relevant ones manually so GTM triggers work.
 *
 * Naming note: Raffle's native widget fires bare `raffle_open` / `raffle_close`
 * for whatever surface is opened (search, chat, …). To let GTM tell the search
 * overlay apart from a separate chat integration, the open/close events here are
 * namespaced (`raffle_search_open` / `raffle_search_close`), while result-level
 * actions keep Raffle's native names.
 *
 * Raffle's own widget sends NO payload (it omits query/answer data for GDPR
 * reasons). We follow that for the open/close/search events, but DO attach the
 * clicked item's URL/title to click events — those are public, non-personal
 * page data, not PII.
 *
 * @see https://docs.raffle.ai/installation/tag-manager/
 *
 * @param {string} name   Event name WITHOUT the `raffle_` prefix
 *                         (e.g. 'search_open', 'answer_click').
 * @param {Object} [data] Optional extra dataLayer properties (e.g. result URL).
 */
export function pushRaffleEvent( name, data = {} ) {
	if ( typeof window !== 'undefined' && window.dataLayer ) {
		window.dataLayer.push( { event: `raffle_${ name }`, ...data } );
	}
}
