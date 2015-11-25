# cat_image_manipulation
Core Purpose:

To be able to use image manipulations on category images available from the native directory



Tag Usage:

`{exp:cat_image_manipulation cat_id="{category_id}" manipulation="wholesale"}`

Returns:
`http://www.example.com/assets/media/products/_wholesale/full_image_name.jpg`


Variables:

cat_id
manipulation



Full Example:

```
{exp:channel:categories channel="wholesale_products" category_group="1" style="linear"}
<h4>{category_name}</h4>
<img src="{exp:cat_image_manipulation cat_id="{category_id}" manipulation="wholesale"}" alt="{category_name}" />
<p>{wholesale_desc}</p>
{/exp:channel:categories}
```


We built this so that we can use native image manipulations on category images. We tend to store all of our category images in the same directory so they all have the same manipulations available. This may not work if your category images are in different directories, if they are though then just make sure each directory has a consistent manipulation name for the plugin to target.