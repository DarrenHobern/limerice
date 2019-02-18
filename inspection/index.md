---
layout: archive
title: "Inspections"
date: 2014-05-30T11:40:45-04:00
modified:
excerpt: " Welcome to Vidtech Services  

Servicing the electrical Industry for more than 30 years
Vidtech Services is able to offer you a personal service in the following fields "
tags: []
image:
  feature:
  teaser:
---

<div class="tiles">
{% for post in site.categories.inspection %}
  {% include inspection-grid.html %}
{% endfor %}
</div><!-- /.tiles -->
