# Grid helpers for Silverstripe

This module provides some basic grid/display choices for Elemental elements, accessible to content editors.

## Features

+ ElementChildGridExtension: apply column selection to an element containing child items (eg. `ElementList`, a gallery with images)
+ ElementDisplayChoiceExtension: apply a display option to an element, for use by templates.
+ ElementHasChildrenExtension: apply layout decisions to an element containing child items (eg. `ElementList`, a list of publications) 

The default grid behaviour is to use the NSW Design System grid, which is quite similar to Bootstrap. You can modify this via configuration.

## Configuration

The `Configuration` model is used to set base grid definitions and return strings for use as CSS classes.

You can modify values to use other grid systems, for instance to use the Bootstrap grid:

```yaml
NSWDPC\GridHelper\Models\Configuration:
  grid_prefix: 'col'
```

Or, for [Reflex Grid](https://reflexgrid.com/#grid-introduction):

```yaml
NSWDPC\GridHelper\Models\Configuration:
  grid_prefix: 'col'
  grid_mapping:
    xs: 'xs'
    sm: 'sm'
    md: 'md'
    lg: 'lg'
    xl: 'xlg'
```

Use `Injector` to provide your own configuration model - just remember to extend `NSWDPC\GridHelper\Models\Configuration`

## Templating

Using the default [ElementalList template](https://github.com/dnadesign/silverstripe-elemental-list/blob/master/templates/DNADesign/ElementalList/Model/ElementList.ss) your template could look something like this:

```html
<% if $ShowTitle && $Title %>
<h2>{$Title}</h2>
<% end_if %>
<% if $Elements.Elements %>
    <div class="grid">
        <% loop $Elements.Elements %>
            <%-- note the scope resolution here --%>
            <div class="{$Up.Up.ColumnClass}">
                {$Me}
            </div>
        <% end_loop %>
    </div>
<% end_if %>
```

The above will use the selected `CardColumns` value. You can pass a value from the template to override the selection  made in the CMS:

```html
<%-- force a two column grid --%>
<% loop $Items %>
<div class="{$Up.Up.ColumnClass(2)}">
    {$Me}
</div>
<% end_loop %>
```

In complex layouts where options are passed in templates via an `<% include ... %>` a variable can be used:

```html
<% include Path/To/Some/Template OverrideColumns=4 %>

<%-- Template.ss: set override based on value provided --%>
<% loop $Items %>
<div class="{$Up.Up.ColumnClass($Up.OverrideColumns)}">
    {$Me}
</div>
<% end_loop %>
```


### Display choice

The `ElementDisplayChoiceExtension` can be applied to any content-related element, to allow a content editor a degree of choice around how that content should be displayed.

Your theme/template itself will determine how this should be handled based on the value provided.

Assuming the ElementDisplayChoiceExtension was applied to ElementContent:

```html
<%-- ElementContent.ss --%>
<% if $Subtype == 'callout' %>
    <%-- render as a callout --%>
<% else_if .... %>

<% end_if %>
```

The module provides a number of default 'sub types' that can be selected, and you can configure your own.

### Consistent display within a list

If the relevant element is a child of an ElementalList, the display choices will be removed from the CMS and the list's display choices should be honoured in your templates. This it to avoid, for instance, a grid of items made up of differently styled content elements.

The list element, and any other Element that has the `ElementHasChildrenExtension` will gain a `List Type` and `Content style` field, for use in defining how the child items should be displayed. The default types selectable are taken from most standard design/component systems (eg. Card, Content Block, Tabs, Link list, List items).

### Templating

As with all things Silverstripe, the frontend/template implementation is entirely up to you. This module just provides some hints as to how things should be displayed. Your theme/template should implement a solution for your user interface/component library of choice.

## Maintainers

+ [dpcdigital@NSWDPC:~$](https://dpc.nsw.gov.au)

## Bugtracker

We welcome bug reports, pull requests and feature requests on the Github Issue tracker for this project.

Please review the [code of conduct](./code-of-conduct.md) prior to opening a new issue.

## Security

If you have found a security issue with this module, please email digital[@]dpc.nsw.gov.au in the first instance, detailing your findings.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.

Please review the [code of conduct](./code-of-conduct.md) prior to completing a pull request.
