# PhileFamilyPlugin
A plugin for providing sibling and ancestor records to Phile themes.  By default, Phile provides `{{ pages }}` to theme templates.  This plugin adds `{{ siblings }}` and `{{ ancestors }}`

**Questions? Comments?** Feel free to throw something in the issue tracker

## Installation
1. Create the directory `plugins/family`
2. Download and place  the repository files in that directory.
3. Modify your config file to include the plugin.  For example:

```php
$config['plugins'] = array(
	'family' => array('active' => true),
);
```

## Usage
Your template files will now have 2 new variables: `{{ siblings }}` and `{{ ancestors}}`.  

### Siblings
* `{{ siblings }}` will contain a Page object for each page & subdirectory in the current directory (except the current page).  
  * For subdirectories, the Page object will point to the index.md file of the subdirectory.  
* `{{ siblings }}` will be sorted in default sort order.

### Ancestors
* `{{ ancestors }}` will contain a Page object for each parent, grandparent, etc. directory.
* If the current page is not the index page for a directory, then the index page will for the current directory will be the first ancestor.
* `{{ ancestors }}` will be sorted from last ancestor to first ancestor (the parent directory)
* Pages in the `{{ ancestors }}` array will have an additional `is_dir` property.

## Options

####`sibling_dirs`
Type: *boolean*
Default: TRUE

`{{ siblings }}` ordinarily contains an entry for every page that is in the same directory as the currently viewed page.  It will also contain an entry for every subdirectory that is in the same directory as the currently viewed page.  The entry for each directory will point to the `index.md` file in the subdirectory.

Setting this option to `FALSE` will cause `{{ siblings }}` to *only* contain entries for pages.

####`ancestor_sort`
Type: *string*
Default: 'asc'

If 'asc' _(or anything other than 'desc')_, the 1st ancestor will be the homepage, and the last ancestor will be the parent.

If 'desc', the 1st ancestor will be the parent, the 2nd the grandparent, etc.


## Examples

Given this content structure:

```
/content/
  index.md
  about.md
  recipes/
    index.md
    pie.md
    cakes/
      index.md
      redvelvet.md
      angelfood.md
```

<table>
  <tr>
    <th>
      viewing
    </th>
    <th>
      Ancestors
    </th>
    <th>
      Siblings
    </th>
  </tr>
  <tr>
    <td>/</td>
    <td>
      NULL
    </td>
    <td>
      /about.md<br />
      /recipes/index.md
    </td>
  </tr>
  <tr>
    <td>/recipes/</td>
    <td>/index.md</td>
    <td>
      /recipes/pie.md<br />
      /recipes/cakes/index.md
    </td>
  </tr>
  <tr>
    <td>/recipes/cakes/redvelvet.md</td>
    <td>
      /index.md<br />
      /recipes/index.md<br />
      /recipes/cakes/index.md      
    </td>
    <td>
      /recipes/cakes/angelfood.md
    </td>
  </tr>
</table>
**Note that when viewing a non-index page (ie: /recipes/cakes/redvelvet.md), the index page of the current directory is the last ancestor.**
