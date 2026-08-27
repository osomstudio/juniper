<?php

namespace Juniper\Fields;

/**
 * Safe accessors for native block attributes, replacing the ACF
 * get_field()/have_rows() ergonomics for blocks built with
 * RepeaterControl and ImagePickerControl.
 *
 * Image and repeater data is already saved in full inside the block
 * attributes by the editor controls, so these helpers only normalise
 * and defend render.php against missing/malformed data - they do not
 * hit the database.
 */
class BlockFields {
	/**
	 * Normalise an image attribute saved by ImagePickerControl.
	 *
	 * @param mixed $image Raw attribute value.
	 * @return array{id?: int, url?: string, alt?: string} Empty array when there is no image.
	 */
	public static function image( mixed $image ): array {
		if ( ! is_array( $image ) || empty( $image['url'] ) ) {
			return array();
		}

		return array(
			'id'  => $image['id'] ?? 0,
			'url' => $image['url'],
			'alt' => $image['alt'] ?? '',
		);
	}

	/**
	 * Normalise a repeater attribute saved by RepeaterControl.
	 *
	 * @param mixed $rows Raw attribute value.
	 * @return array<int, array<string, mixed>>
	 */
	public static function rows( mixed $rows ): array {
		return is_array( $rows ) ? array_values( $rows ) : array();
	}

	/**
	 * Read a single value out of a repeater row with a default fallback,
	 * mirroring ACF's get_sub_field() default-value behaviour.
	 *
	 * @param array<string, mixed> $row      A single repeater row.
	 * @param string                $name     Field name within the row.
	 * @param mixed                 $fallback Value when the row's field is missing/empty.
	 * @return mixed
	 */
	public static function row_value( array $row, string $name, mixed $fallback = '' ): mixed {
		return $row[ $name ] ?? $fallback;
	}
}
