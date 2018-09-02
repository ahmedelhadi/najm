<?php
/*
Plugin Name: GravityWP - Count
Plugin URI: http://gravitywp.com/plugins/count/
Description: Adds a shortcode to count, filter and display Gravity Forms entries or the total of a number field in multiple entries.
Author: GravityWP
Version: 0.9.3
Author URI: http://gravitywp.com/plugins/count/
License: GPL2
*/
if ( class_exists( "GFForms" ) ) {

	function gravitywp_count_func( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'formid'           => '0',
			'formstatus'       => 'active',
			'number_field'     => false,
			'filter_mode'      => 'all',
			'add_number'       => '0',
			'filter_field'     => false,
			'filter_operator'  => 'is',
			'filter_value'     => false,
			'filter_field2'    => false,
			'filter_operator2' => 'is',
			'filter_value2'    => false,
			'filter_field3'    => false,
			'filter_operator3' => 'is',
			'filter_value3'    => false,
			'filter_field4'    => false,
			'filter_operator4' => 'is',
			'filter_value4'    => false,
			'filter_field5'    => false,
			'filter_operator5' => 'is',
			'filter_value5'    => false,
			'decimals'         => '2',
			'dec_point'        => '.',
			'thousands_sep'    => ',',
			'created_by'       => '',
			'page_size'        => '200', // Set to 200 per the GF API documentation: https://docs.gravityforms.com/api-functions/#get-entries
			'is_starred'       => '',
			'is_read'          => '',
			'is_approved'      => '',
			'multiply'         => '1',
			'start_date'       => false,
			'end_date'         => false,
			'workflow_step' => '', // Takes the step number
			'workflow_step_status' => 'pending', // String separated by commas
			'workflow_step_is_current' => true // Getting entries pause at the step when set to true; getting entries have moved forward to other steps when set to false.
		), $atts ) );

		if ( $formstatus != 'all' ) {
			$search_criteria['status'] = $formstatus;
		}

		$search_criteria['field_filters']['mode'] = $filter_mode;

		if ( ! empty( $filter_field ) ) {
			$search_criteria['field_filters'][] = array( 'key'      => $filter_field,
														 'operator' => $filter_operator,
														 'value'    => $filter_value
			);
		}
		if ( ! empty( $filter_field2 ) ) {
			$search_criteria['field_filters'][] = array( 'key'      => $filter_field2,
														 'operator' => $filter_operator2,
														 'value'    => $filter_value2
			);
		}
		if ( ! empty( $filter_field3 ) ) {
			$search_criteria['field_filters'][] = array( 'key'      => $filter_field3,
														 'operator' => $filter_operator3,
														 'value'    => $filter_value3
			);
		}
		if ( ! empty( $filter_field4 ) ) {
			$search_criteria['field_filters'][] = array( 'key'      => $filter_field4,
														 'operator' => $filter_operator4,
														 'value'    => $filter_value4
			);
		}
		if ( ! empty( $filter_field5 ) ) {
			$search_criteria['field_filters'][] = array( 'key'      => $filter_field5,
														 'operator' => $filter_operator5,
														 'value'    => $filter_value5
			);
		}
		if ( ! empty( $created_by ) ) {
			if ( $created_by == "current" ) {
				$user_ID = get_current_user_id();
				$search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $user_ID );
			} else {
				$search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $created_by );
			}
		}

		if ( $is_starred == "yes" ) {
			$search_criteria['field_filters'][] = array( 'key' => 'is_starred', 'value' => true );
		}
		if ( $is_starred == "no" ) {
			$search_criteria['field_filters'][] = array( 'key' => 'is_starred', 'value' => false );
		}

		if ( $is_read == "yes" ) {
			$search_criteria['field_filters'][] = array( 'key' => 'is_read', 'value' => true );
		}
		if ( $is_read == "no" ) {
			$search_criteria['field_filters'][] = array( 'key' => 'is_read', 'value' => false );
		}

		if ( class_exists( 'GravityView_Entry_Approval_Status' ) ) {
			if ( $is_approved == "yes" ) {
				$search_criteria['field_filters'][] = array( 'key'   => GravityView_Entry_Approval::meta_key,
															 'value' => GravityView_Entry_Approval_Status::APPROVED
				);
			}
			if ( $is_approved == "no" ) {
				$search_criteria['field_filters'][] = array( 'key'   => GravityView_Entry_Approval::meta_key,
					'is_approved',
															 'value' => GravityView_Entry_Approval_Status::DISAPPROVED
				);
			}
		}

		if ( ! empty( $start_date ) ) {
			$date_start = date_create( $start_date );
			if ( $date_start ) {
				$search_criteria['start_date'] = $date_start->format( 'Y-m-d H:i:s' );
			} else {
				echo "Oops... your start_date format is not correct: " . $start_date . " (must be Month/Day/Year)<br>Change the start_date in your gravitywp_count shortcode.<br>";
			}
		}

		if ( ! empty( $end_date ) ) {
			$date_end = date_create( $end_date );
			if ( $date_end ) {
				$search_criteria['end_date'] = $date_end->format( 'Y-m-d H:i:s' );
			} else {
				echo "Oops... your send_date format is not correct: " . $end_date . " (must be Month/Day/Year)<br>Change the start_date in your gravitywp_count shortcode.<br>";
			}
		}

		if ( class_exists( 'Gravity_Flow_Bootstrap' ) ) {
			if ( ! empty( $workflow_step ) ) {
				if ( strstr( $workflow_step_status, ',' ) ) {
					$workflow_step_status = explode( ',', $workflow_step_status );
				}

				if ( is_array( $workflow_step_status ) ) {
					$search_criteria['field_filters'][] = array(
						'key' => 'workflow_step_status_' . $workflow_step,
						'operator' => 'in',
						'value' => array_map( 'trim', $workflow_step_status )
					);
				} else {
					$search_criteria['field_filters'][] = array(
						'key' => 'workflow_step_status_' . $workflow_step,
						'value' => $workflow_step_status
					);
				}

				if ( 'false' !== $workflow_step_is_current ) {
					$search_criteria['field_filters'][] = array(
						'key' => 'workflow_step',
						'value' => $workflow_step
					);
				}
			}
		}

		$sorting          = null;
		$paging           = array( 'offset' => 0, 'page_size' => $page_size );
		$countentries     = GFAPI::count_entries( $formid, $search_criteria );
		$countentries_add = $add_number + $countentries;
		if ( ! empty( $number_field ) ) {
			$entries          = GFAPI::get_entries( $formid, $search_criteria, $sorting, $paging );

			$gwp_count = $add_number;
			for ( $row = 0; $row < $countentries; $row ++ ) {
				$gwp_count += $entries[ $row ][ $number_field ];
			}

			return number_format( $gwp_count * $multiply, $decimals, $dec_point, $thousands_sep );
		} else {
			return number_format( $countentries_add * $multiply, "0", ".", $thousands_sep );
		}
	}

	add_shortcode( 'gravitywp_count', 'gravitywp_count_func' );

}
