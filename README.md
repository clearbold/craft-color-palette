# Color Palette

Configure color themes for your Craft CMS site. Select color themes for page or component styles via entry fields. Output CSS class names or color values in your templates.

## Roadmap

* [ ] Retain deleted collections & themes so they remain available to fields where they're selected
* [ ] Import/export functionality
* [ ] Display contrast scores for adjacent colors, for accessibility
* [ ] Better color picker, incorporating alpha
* [ ] UI enhancements

## Twig Examples
```
<style>
{% for color in entry.field.all() %}
    --color-{{ color.handle}}: {{ color.getHex() }};
{% endfor %}
</style>

{# Outputs: #}

<style>
    --color-background: #CC4224;
    --color-text: #000000;
    --color-button: #2A75B3;
    --color-highlight: #F3D403;
</style>
```

```
<style>
    --color-background: {{ entry.field.handle('background').getRgb() }}
    --color-background-alpha: {{ entry.field.handle('background').getRgba() }}
</style>

{# Outputs: #}

<style>
    --color-background: rgb(204, 66, 36);
    --color-background-alpha: rgb(204, 66, 36, .5);
</style>
```

```
<style>
{% set collections = craft.colorpalette.collections %}
{# or #}
{% for collection in craft.colorpalette.all() %}

{% set themes = collections.themes %}
{# or #}
{% for theme in collection.all() %}
{% for color in theme.colors %}
    --color-{{ color.handle}}: {{ color.getHex() }};
{% endfor %}
{% endfor %}
{% endfor %}
</style>

{# Outputs: #}

<style>
    --color-background: #CC4224;
    --color-text: #000000;
    --color-button: #2A75B3;
    --color-highlight: #F3D403;
</style>
```

```
<div class="color-{{ entry.field.collection }} color-{{ entry.field.theme }}"></div>

{# Outputs #}

<div class="color-heroes color-captainMarvel"></div>
```

```
{% set collection = craft.colorpalette.handle('heroes') %}
{% set theme = collection.handle('captainMarvel') %}
{# or #}
{% set collection = craft.colorpalette.collection('heroes') %}
{% set theme = collection.theme('captainMarvel') %}
{# or #}
{{ craft.colorpalette.collection('heroes').theme('captainMarvel').name }}

<style>
{% for color in theme.colors %}
    --color-{{ color.handle}}: {{ color.getHex() }};
{% endfor %}
</style>

{# Outputs: #}

<style>
    --color-background: #CC4224;
    --color-text: #000000;
    --color-button: #2A75B3;
    --color-highlight: #F3D403;
</style>

{# ~~~~~~~~ #}

{% set color = theme.color('background') %}
{# or #}
{% set color = theme.handle('background') %}
<style>
    --color-background: {{ color.getRgb() }}
</style>

{# Outputs: #}

<style>
    --color-background: rgb(204, 66, 36);
</style>
```