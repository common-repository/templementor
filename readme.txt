=== Templementor - Persistent Elementor Templates ===
Contributors: LCweb Projects
Donate link: https://lcweb.it/donations
Tags: elementor, page template, wordpress builder, page builder, page builder templates, header, footer, persistent
Requires at least: 3.5
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Makes Elementor even greater by creating persistent templates to shape up your website in minutes!

== Description ==

Yes, [Elementor](https://elementor.com/?ref=1930) builder is absolutely great, but using it a **major downside** is evident: **we have to edit each page singularly**. This is quite a problem when you have many pages with identical elements (eg. sidebars, head, footer).

Templementor is a perfect solution, pushing Elementor limits:

1.  **Create templates** directly through [Elementor](https://elementor.com/?ref=1930). You can create completely new page layouts by using "Elementor Canvas"
 
2.  Insert the **{{contents}} placeholder** wherever you prefer in the template, preferably in an HTML block (_continue reading to know more about placeholders_)

3.  **Apply templates** to any post (page, etc) editable through [Elementor](https://elementor.com/?ref=1930)

Page contents will be wrapped by the template. 
Have you applied the template to 100 pages? 

Just edit it to **magically update also affected pages!** Isn't it great?

Affected page will inherit also template page settings (eg. background and padding). 
You could theoretically **build an entire site, with wonderful graphic, without a premium theme** and maintain/update it in minutes!

Advanced users can also apply template to existing templates. For example you could have different head sections while keeping the same footer, without needing to edit footer section for each head template.
 

### Placeholders ###

Placeholders are essentials in Templementor: in fact having only page contents replacement wouldn't be a great deal, isn't it?

You can theoretically **use unlimited placeholders to display posts data** into templates:

*   **{{contents}}** - page contents
*   **{{title}}** - page's title
*   **{{author}}** - page's author _(its nicename)_
*   **{{pub-date}}** - page's creation date _(global date format used)_
*   **{{edit-date}}** - page's modification date _(global date format used)_
*   **{{excerpt}}** - page's excerpt
*   **{{comm-count}}** - page comments count
*   **{{POST-META-KEY-NAME}}** - page's custom field value

Obviously replace _POST-META-KEY-NAME_ with the proper meta name. They are widely used by plugins to store data and you can use it into templates. You could also create them with the maximum ease through WP editor wizard.


= Notes =
No support provided
 

== Installation ==

1. [Elementor](https://elementor.com/?ref=1930) plugin must be installed
2. Upload `templementor` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. A new submenu item will be visible under "Elementor" menu
5. Create templates and use _{{contents}}_ as mandatory placeholder
5. Apply them through the sidebar dropdown you find in any page/post editor


== Screenshots ==

1. Template 
1. Vanilla page (as it would be shown)
1. Templated page
1. Templates management page
1. How to apply templates

== Changelog ==

= 1.0.1 =
Avoid enqueuing templates CSS since Elementor already does it in footer 

= 1.0 =
Initial release

== Upgrade notice ==

none