# GravityWP-Count

Display the number of entries in Gravity Forms (and filter them). Or count and display the total for multiple entries of a number field in Gravity Forms and show it on your website with this simple shortcode. 

## Getting Started

Download and install the zip in your WordPress or install using the [WordPress plugin repository](https://wordpress.org/plugins/gravitywp-count/) 

## How to use the plugin

Most simple version of the shortcode (display number of total entries Gravity Forms for a form):
```
[gravitywp_count formid=""]
```

Or when you count a number field (display total count of a number field for all entries a specific Gravity Form):
```
[gravitywp_count formid="" number_field=""]
```

### Filter options
The most extensive version of the shortcode (display the total count of a number field from multiple Gravity Forms entries with up to five filters and input for number of decimals, the decimal point notation and the thousand seperator, etc, etc):
```
[gravitywp_count formid="" formstatus="" number_field="" filter_mode="" filter_field="" filter_operator="" filter_value="" filter_operator2="" filter_field2="" filter_value2="" filter_field3="" filter_operator3="" filter_value3="" filter_field4="" filter_operator4="" filter_value4="" filter_field4="" filter_operator4="" filter_value4="" decimals="" dec_point="" thousands_sep="" is_read="yes" is_starred="no" page_size="1000″ created_by="1″ multiply="2″ start_date="12/31/2016″ end_date="12/31/2017″]
```

## Authors

* **Erik van Beek** - *Initial work* - [Personal website](http://erikvanbeek.nl/)
* **Yoren Chang** - *Adding Gravity Flow filters* - [Hire Yoren on Codeable](https://codeable.io/developers/yoren-chang/)
