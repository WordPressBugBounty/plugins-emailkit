<?php 

namespace EmailKit\Admin\Emails\Helpers;

defined("ABSPATH") || exit;

class Utils
{
    public static function get_kses_array()
    {
        return array(
            'html'                          => array(),
            'head'                          => array(),
            'body'                          => array(),
            'hr'                            => array(),
            'address'                       => array(),
            'a'                             => array(
                'class'  => array(),
                'href'   => array(),
                'rel'    => array(),
                'title'  => array(),
                'target' => array(),
                'style'  => array(),
            ),
            'abbr'                          => array(
                'title' => array(),
                'style'  => array(),
            ),
            'b'                             => array(
                'class' => array(),
                'style'  => array(),
            ),
            'blockquote'                    => array(
                'cite' => array(),
                'style'  => array(),
            ),
            'cite'                          => array(
                'title' => array(),
                'style'  => array(),
            ),
            'code'                          => array(
                'style'  => array(),
            ),
            'pre'                           => array(
                'style'  => array(),
            ),
            'del'                           => array(
                'datetime' => array(),
                'title'    => array(),
                'style'  => array(),
            ),
            'dd'                            => array(
                'style'  => array(),
            ),
            'div'                           => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl'                            => array(
                'style' => array(),
            ),
            'dt'                            => array(
                'style' => array(),
            ),
            'em'                            => array(
                'style' => array(),
            ),
            'strong'                        => array(
                'style' => array(),
            ),
            'h1'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h2'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h3'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h4'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h5'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'h6'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'i'                             => array(
                'class' => array(),
                'style' => array(),
            ),
            'img'                           => array(
                'alt'        => array(),
                'class'        => array(),
                'height'    => array(),
                'src'        => array(),
                'width'        => array(),
                'style'        => array(),
                'title'        => array(),
                'srcset'    => array(),
                'loading'    => array(),
                'sizes'        => array(),
                'style' => array(),
            ),
            'figure'                        => array(
                'class'        => array(),
                'style' => array(),
            ),
            'li'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'ol'                            => array(
                'class' => array(),
                'style' => array(),
            ),
            'p'                             => array(
                'class' => array(),
                'style' => array(),
            ),
            'q'                             => array(
                'cite'  => array(),
                'title' => array(),
                'style' => array(),
            ),
            'span'                          => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'iframe'                        => array(
                'width'       => array(),
                'height'      => array(),
                'scrolling'   => array(),
                'frameborder' => array(),
                'allow'       => array(),
                'src'         => array(),
                'style' => array(),
            ),
            'strike'                        => array(),
            'br'                            => array(),
            'table'                         => array(),
            'thead'                         => array(),
            'tbody'                         => array(
                'width'       => array(),
                'height'      => array(),
                'scrolling'   => array(),
                'frameborder' => array(),
                'allow'       => array(),
                'src'         => array(),
                'style' => array()
            ),
            'tfoot'                         => array(),
            'tr'                            => array(
                'width'       => array(),
                'height'      => array(),
                'scrolling'   => array(),
                'frameborder' => array(),
                'allow'       => array(),
                'src'         => array(),
                'style' => array()
            ),
            'td'  => array(
                'class' => array(),
            ),
            'th'                            => array(),
            'colgroup'                      => array(),
            'col'                           => array(),
            'strong'                        => array(),
            'data-wow-duration'             => array(),
            'data-wow-delay'                => array(),
            'data-wallpaper-options'        => array(),
            'data-stellar-background-ratio' => array(),
            'ul'                            => array(
                'class' => array(),
            ),
            'button'                        => array(
                'disabled' => array(),
                'id' => array(),
                'class' => array(),
                'style' => array(),
                'target' => array(),
                'href' => array(),
                'data-editor-template-url' => array(),
                'data-emailkit-email-type' => array(),
                'data-emailkit-template-title' => array(),
                'data-emailkit-template-type' => array(),
                'data-emailkit-template' => array(),
            ),
            'svg'                           => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true, // <= Must be lower case!
                'preserveaspectratio' => true,
            ),
            'g'                             => array('fill' => true),
            'title'                         => array('title' => true),
            'path'                          => array(
                'd'    => true,
                'fill' => true,
            ),
            'input'                            => array(
                'class'        => array(),
                'type'        => array(),
                'value'        => array()
            )
        );
    }

    public static function kses($raw)
    {

        $allowed_tags = self::get_kses_array();

        if (function_exists('wp_kses')) { // WP is here
            return wp_kses($raw, $allowed_tags);
        } else {
            return $raw;
        }
    }

    public static function mail_shortcode_filter(string $input) : string {
        
        // find the shortcode
        $pattern = '/<span data-shortcode="{{([^<>]+)}}">([^<>]+)<\/span>/';

        //  getting all shortcode matches from mail content 
        preg_match_all($pattern, $input, $matches, PREG_SET_ORDER);

        // Loop through each match and replace the content inside the span tag
        foreach ($matches as $match) {
            $shortcode = $match[1]; // Content inside the data-shortcode attribute
            $oldContent = $match[2]; // Content inside the span tag

            // replaceing the shortcode 
            $replacement = '<span>{{' . $shortcode . '}}</span>';
            $input = str_replace($match[0], $replacement, $input);
        }
        
        return  $input;
    }

    public static function order_items_replace(string $document, array $replacements ){
		
		$pattern = '/<tr[^>]*class="order_items"[^>]*>.*?<\/tr>/s';
		preg_match_all($pattern, $document, $matches);

		if (!empty($matches[0])) {
			$row = '';
			// Iterate through each matched row
			foreach ($matches[0] as $originalRow) {
				// Duplicate the row $n times
				$duplicatedRow = $originalRow;

				// Replace placeholders in the duplicated row
				$placeholders = ["{{product_name}}", "{{quantity}}", "{{total}}", "{{product_price}}"];
				
				// Replace placeholders in each duplicated row
				foreach ($replacements as $replacement) {
					$row .= str_replace($placeholders, $replacement, $duplicatedRow);
				}
			}
			return  preg_replace($pattern, $row, $document);
		}

		return $document;
	}

     // Helper function to escape quotes
     public static function escape_quotes($data) {
        if (is_string($data)) {
            return addslashes($data);
        } elseif (is_array($data)) {
            return array_map('escape_quotes', $data);
        }
        return $data;
    }

    // Function to transform keys from {{order_number}} format to [order_number] format
    public static function transform_details_keys($details) {
        $transformed_details = [];
        foreach ($details as $key => $value) {
            $new_key = str_replace(['{{', '}}'], ['[', ']'], $key);
            $transformed_details[$new_key] = $value;
        }
        return $transformed_details;
    }
}