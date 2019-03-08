---
layout: default
---
{% comment %} Validation for contact form {% endcomment %}
 {% include contact-submit.php %}
<div id="main" role="main">
	<div class="wrap">
		{% if page.image.feature %}
		<div class="page-feature">
			<div class="page-image">
				<img src="{{ site.url }}/images/{{ page.image.feature }}" class="page-feature-image" alt="{{ page.title }}">
				{% if page.image.credit %}{% include image-credit.html %}{% endif %}
			</div><!-- /.page-image -->
		</div><!-- /.page-feature -->
		{% endif %}
		<div class="page-title">
			<h1>{{ page.title }}</h1>
		</div>
		<div class="archive-wrap">
			<div class="page-content">
				{{ content }}
        {% comment %} contact form {% endcomment %}
				{% include contact.html %}
			</div><!-- /.page-content -->
		</div class="archive-wrap"><!-- /.archive-wrap -->
	</div><!-- /.wrap -->
</div><!-- /#main -->
