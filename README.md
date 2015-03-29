pyro-youtube-field
==================

Convert a link to a Youtube to an array filled with the title, id, and url.

## How It Works

The input:

```
https://www.youtube.com/watch?v=Uc6NdG4xuXw
```

The output:

```
array(
    'id' => 'Uc6NdG4xuXw',
    'title' => 'WARPAINT Chalk Wall',
    'url' => 'https://www.youtube.com/watch?v=Uc6NdG4xuXw'
    );
```

This field will take in the URL for a Youtube video and resolve the Title and the ID. Then this data could be used to generate a link, or create an iFrame-embed video.

## How To Use

### Basics

* Rename this folder to `youtube_video`
* Add the field type as you would normally
* Choose "Youtube Link" as the type
* Enjoy it on a page

### In the layout

This field returns an array for the link that you entered. Here is the basic usage.

```html
<!-- make sure the ID was resolved -->
{{ if page:video_field_slug:id }}
  <iframe title="{{ page:video_field_slug:title }}" class="embed-responsive-item" width="640" height="360" src="https://www.youtube.com/embed/{{ page:video_field_slug:id }}?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen=""></iframe>
  <a href="{{ page:video_field_slug:url }}" title="{{ page:video_field_slug:title }}">Watch {{ page:video_field_slug:title }}</a>
{{ endif }}
```

