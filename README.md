# PaginationHelper

**PaginationHelper** is a plugin for the [Grav](grav) CMS  to allow dividing of collections to discrete pages.

This plugin is aimed at allowing theme developers to easily implement a pagination. This plugin is not intended to be a "copy-paste and get-pagination" plugin for site owners to quickly implement a pagination.

As such, you must create your own template that makes use of the pagination helper. We do not supply one for you.

## Installation

To install this plugin, simply download the latest release of this plugin and unzip it under `/grav_base_dir/user/plugins`. Then, rename the folder to `PaginationHelper`.

You should now have all the plugin files under

	/grav_base_dir/user/plugins/PaginationHelper

# Usage / Tutorial

Before using this theme, you must make sure that your collection page is set up correctly.

Firstly, you must make sure that you explicitly enable the pagination option in your collection, by setting `page.header.content.pagination` to `enabled`. Any value other than `enabled` will not be used.

You must also make sure that `page.header.content.limit` is set to the number of pages you would like in a single discrete pagination page.

An example of a correctly set up collection page:

	---
	title: Posts
	content:
	    items: @self.children
	    limit: 5
	    pagination: enabled
	---

The PaginationHelper provides a theme with one variable: `pagination`. This variable can be accessed by `page.collection.params.pagination`.

Inside the variable, you get the following sub-variables:

| Name    | Type    | Description                                                                         |
| ------- | ------- | ----------------------------------------------------------------------------------- |
| current | Integer | The page number of the current pagination page.                                     |
| pages   | Array   | An array of PaginationPage, which are pagination pages (in case it wasn't obvious). |
| hasPrev | Boolean | Whether the current pagination page has a next pagination page.                     |
| hasNext | Boolean | Whether the current pagination page has a previous pagination page.                 |
| prevUrl | String  | Previous pagination page URL.                                                       |
| nextUrl | String  | Next pagination page URL.                                                           |

Before displaying the pagination, you should check if a pagination need to be displayed. For example, it will be unnecessary for a pagination to be displayed when there are a total post of less than the pagination limit.

As an example, if you wanted to use these variables to form a simple template for a newer/older pagination:

	{# Check if a pagination should be displayed or not. #}
	{% if page.collection.params.pagination.pages|length > 1 %}
	    <div class="pagination">
	        {% if page.collection.params.pagination.hasPrev %}
	            <a style="float: left;" href="{{ page.url ~ page.collection.params.pagination.prevUrl }}"><span class="octicon octicon-chevron-left"></span> Newer</a>
	        {% endif %}
	
	        {% if page.collection.params.pagination.hasNext %}
	            <a style="float: right;" href="{{ page.url ~ page.collection.params.pagination.nextUrl }}">Older <span class="octicon octicon-chevron-right"></span></a>
	        {% endif %}
	
	        <div style="clear: both;"></div>
	    </div>
	{% endif %}

For a more advanced example taken from Team Grav's original implementation (fixed by me):

	{# Check if a pagination should be displayed or not. #}
	{% if page.collection.params.pagination.pages|length > 1 %}
		<ul class="pagination">
		    {% if page.collection.params.pagination.hasPrev %}
		        <li><a href="{{ page.url ~ page.collection.params.pagination.prevUrl }}">&laquo;</a></li>
		    {% else %}
		        <li><span>&laquo;</span></li>
		    {% endif %}
		
		    {% for paginate in page.collection.params.pagination.pages %}
		
		        {% if paginate.isCurrent %}
		            <li><span>{{ paginate.number }}</span></li>
		        {% else %}
		            <li><a href="{{ page.url ~ paginate.url }}">{{ paginate.number }}</a></li>
		        {% endif %}
		
		    {% endfor %}

		    {% if page.collection.params.pagination.hasNext %}
		        <li><a href="{{ page.url ~ page.collection.params.pagination.nextUrl }}">&raquo;</a></li>
		    {% else %}
		        <li><span>&raquo;</span></li>
		    {% endif %}
		</ul>
	{% endif %}

You should make your pagination a partial template named `partials/pagination.html.twig` but that decision is ultimately up to you as a theme designer/developer.

Lastly, include the pagination in your theme like following. Before including the pagination, you should make sure you check to see if the collection actually requests a pagination. If it does not, the template will try to use variables that aren't defined (because the PaginationHelper doesn't define the `pagination` variable if the pagination option is not enabled) which may cause some weird errors.

	{% if page.header.content.pagination == 'enabled' %}
	        {% include 'partials/pagination.html.twig' %}
    {% endif %}

That's it! You should now see the pagination. If you don't see the pagination, have question, found a bug or a typo etc. please feel free to contact me via email, or open an issue. I will try to respond ASAP.

I have tested this plugin to work in my own projects. But if it doesn't work, again, feel free to contact me.

Enjoy!