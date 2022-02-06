<?php

/* Demo use: 
	$generator = new TitleKeyURLGenerator();
	
	print_r($generator->GenerateAllValuesForEntry([
		'entry'=>[
			'Title'=>'The Best Blog In the World',
			'Subtitle'=>'An "Inside" Look in 2000',
			'Description'=>'',
			'tags'=>[
				'awesome',
				'best',
				'viral',
			],
		],
	]));
*/

	trait ListTitleKeyGenerator {
		public function GenerateEntryListTitleSortKey($args) {
			$list_title = $args['title'];
			
			$list_title = str_replace('“', '', $list_title);
			$list_title = str_replace('”', '', $list_title);
			$list_title = str_replace('"', '', $list_title);
			
			return $this->GenerateEntryListTitle_FullSubTitle(['title'=>$list_title]);
		}
		
		public function GenerateEntryListTitle_ExpandNumbers($args) {
			$list_title = $args['title'];
			
			$expand = 10;
			
			$list_pieces = explode(' ', $list_title);
			$list_pieces_count = count($list_pieces);
			
			$acceptable_pieces = [
				',',
				'!',
				'?',
				'.',
			];
			
			$acceptable_pieces_count = count($acceptable_pieces);
			
			for($i = 0; $i < $list_pieces_count; $i++) {
				$list_piece = $list_pieces[$i];
				
				for($j = 0; $j < $acceptable_pieces_count; $j++) {
					$acceptable_piece = $acceptable_pieces[$j];
					
					$list_pieces_explosion = explode($acceptable_piece, $list_piece);
					$list_pieces_explosion_count = count($list_pieces_explosion);
					
					for($k = 0; $k < $list_pieces_explosion_count; $k++) {
						$list_pieces_explosion_subpiece = $list_pieces_explosion[$k];
						
						if(is_numeric($list_pieces_explosion_subpiece)) {
							$list_pieces_explosion_subpiece_int = (int)$list_pieces_explosion_subpiece;
							
							if($list_pieces_explosion_subpiece == $list_pieces_explosion_subpiece_int && strlen($list_pieces_explosion_subpiece_int) < $expand) {
							#	print("BT: equal?" . $list_piece . ' | ' . $list_piece_int . '|<BR><BR>');
								$new_list_pieces_explosion_subpiece = str_pad($list_pieces_explosion_subpiece, $expand, '0', STR_PAD_LEFT);
								
								$list_pieces_explosion[$k] = $new_list_pieces_explosion_subpiece;
							}
						}
					}
					
					$list_piece = implode($acceptable_piece, $list_pieces_explosion);
				}
				
				$list_pieces[$i] = $list_piece;
			}
			
			$list_title = implode(' ', $list_pieces);
			
			return $list_title;
		}
		
		public function GenerateEntryListTitle_FullSubTitle($args) {
			$list_title = $args['title'];
			
			$list_title = str_replace(',', '', $list_title);
			$list_title = preg_replace('/\s+/', ' ', $list_title);
			
			$list_title = $this->GenerateEntryListTitle_ExpandNumbers(['title'=>$list_title]);
			
			return $this->GenerateEntryListTitle_SubTitle(['title'=>$list_title]);
		}
	}

	trait ListTitleGenerator {
		public function GenerateEntryListTitle($args) {
			$title = $args['title'];
			
			return $this->GenerateEntryListTitle_SubTitle(['title'=>$title]);
		}
		
		public function GenerateEntryListTitle_SubTitle($args) {
			$list_title = $args['title'];
			
			$list_title = preg_replace('/\s+/', ' ', $list_title);
			
			$special_delimiter = ' _~!~_ ';
			
			$title_operators = [
				'a',
				'an',
				'the',
			];
			$title_operators_count = count($title_operators);
			
			$explosion_operators = [
				' -- ',
				' - ',
				': ',
			];
			$explosion_operators_count = count($explosion_operators);
			
			$all_list_title_pieces = [$list_title];
			$all_list_title_piece_count = count($all_list_title_pieces);
			
			for($i = 0; $i < $explosion_operators_count; $i++) {
				$explosion_operator = $explosion_operators[$i];
				
				$new_all_list = [];
				
				for($j = 0; $j < $all_list_title_piece_count; $j++) {
					$list_title_piece = $all_list_title_pieces[$j];
					
					$new_title_pieces = explode($explosion_operator, $list_title_piece);
					$new_title_pieces_count = count($new_title_pieces);
					
					if($new_title_pieces_count <= 1) {
						$new_all_list[] = $list_title_piece;
					} else {
						for($k = 0; $k < $new_title_pieces_count; $k++) {
							$new_title_piece = $new_title_pieces[$k];
							if($k + 1 < $new_title_pieces_count) {
								$new_all_list[] = $new_title_piece . $special_delimiter . $explosion_operator;
							} else {
								$new_all_list[] = $new_title_piece;
							}
						}
					}
				}
				
				$all_list_title_pieces = $new_all_list;
				$all_list_title_piece_count = count($all_list_title_pieces);
			}
			
			$full_title_text = '';
			
			for($i = 0; $i < $all_list_title_piece_count; $i++) {
				$list_piece = $all_list_title_pieces[$i];
				
				$list_piece_subpieces = explode(' ', $list_piece);
				$first_list_subpiece = $list_piece_subpieces[0];
				$first_list_subpiece_comparable = strtolower($first_list_subpiece);
				
				$found = 0;
				
				for($j = 0; $j < $title_operators_count; $j++) {
					$title_operator = $title_operators[$j];
					
					if($title_operator == $first_list_subpiece_comparable) {
						$found = 1;
						$j = $title_operators_count;
					}
				}
				
				if($found) {
					$first_title_piece_subpiece = $list_piece_subpieces[0];
					unset($list_piece_subpieces[0]);
				}
			
				$new_title_piece = implode(' ', $list_piece_subpieces);
				
				$new_title_piece_reexplode = explode($special_delimiter, $new_title_piece);
				$new_title_piece_reexplode_count = count($new_title_piece_reexplode);
				
				$phrase_separator = FALSE;
				if($new_title_piece_reexplode_count > 1) {
					$phrase_separator = $new_title_piece_reexplode[$new_title_piece_reexplode_count - 1];
					
					unset($new_title_piece_reexplode[$new_title_piece_reexplode_count - 1]);
				}
				$new_title_piece = implode($special_delimiter, $new_title_piece_reexplode);
				
				if($found) {
					$new_title_piece .= ', ' . $first_title_piece_subpiece;
				}
				
				if($phrase_separator) {
					$new_title_piece .= $phrase_separator;
				}
				
				$full_title_text .= $new_title_piece;
			}
			
			return ucfirst($full_title_text);
		}
	}

	trait URLCodeGenerator {
		public function GenerateURLCodeFromValues_MaxLength() {
			return 45;
		}
		
		public function isUserAdmin() {
			return TRUE;	# you should wire this into your own system's checks for admin-checking
		}
		
		public function getUserId() {
			return 10;	# you should wire this into your own system's checks for user-id
		}
		
		public function GenerateURLCodeFromValues($args) {
			$values = $args['values'];
			
			$pieces = [];
			
			$current_length = -1;
			
			$max_length = $this->GenerateURLCodeFromValues_MaxLength();
			
			for($i = 0; $i < count($values); $i++) {
				$value = $values[$i];
				
				$generate_value_code = $this->GenerateURLCodeFromValue([
					'value'=>$value,
					'current_length'=>$current_length,
					'pieces'=>$pieces,
				]);
				
				$current_length = $generate_value_code['current_length'];
				$pieces = $generate_value_code['pieces'];
				
				if($current_length === $max_length) {
					$i = count($values);
				}
			}
			
			$good_code = implode('-', $pieces);
			$good_code = $this->GenerateURLCodeFromValues_UserSubmission(['code'=>$good_code]);
			return $good_code;
		}
		
		public function GenerateURLCodeFromValue($args) {
			$new_value = $args['value'];
			$pieces = $args['pieces'];
			$current_length = $args['current_length'];
			
			if(strlen($new_value) === 0) {
				return [
					'pieces'=>$pieces,
					'current_length'=>$current_length,
				];
			}
				# MORE: https://www.fileformat.info/info/unicode/block/latin_supplement/index.htm
			$max_length = $this->GenerateURLCodeFromValues_MaxLength();
			$new_value = $this->GenerateURLCodeFromValue_FormatValue(['value'=>$new_value]);
			
			$new_value_pieces = preg_split('/[\s]+/', $new_value);
			
			for($i = 0; $i < count($new_value_pieces); $i++) {
				$new_value_piece = $new_value_pieces[$i];
				
				if($current_length === $max_length) {
					$i = count($new_value_pieces);
				} elseif($current_length < $max_length) {
					if($new_value_piece !== '-') {
						$formatted_piece = preg_replace('/[\x{0300}-\x{036f}]/u','',normalizer_normalize($new_value_piece, Normalizer::FORM_D));
						if((strlen($formatted_piece) + $current_length + 1) <= $max_length) {
							$pieces[] = $formatted_piece;
							$current_length += strlen($formatted_piece) + 1;
						}
					}
				}
			}
			
			return [
				'pieces'=>$pieces,
				'current_length'=>$current_length,
			];
		}
		
		public function GenerateURLCodeFromValue_FormatValue($args) {
			$new_value = $args['value'];
			
			$new_value = mb_strtolower($new_value, 'UTF-8');
			
			$string_replacements = $this->GenerateURLCodeFromValue_FormatValue_stringReplacements();
			$new_value = str_replace(array_keys($string_replacements), array_values($string_replacements), $new_value);
			
			$regex_replacements = $this->GenerateURLCodeFromValue_FormatValue_regexReplacements();
			$new_value = preg_replace(array_keys($regex_replacements), array_values($regex_replacements), $new_value);
			
			return $new_value;
		}
		
		public function GenerateURLCodeFromValue_FormatValue_stringReplacements() {
			return [
				'@'=>'at',
				'&'=>'and',
				'Æ'=>'ae',
				'æ'=>'ae',
			];
		}
		
		public function GenerateURLCodeFromValue_FormatValue_regexReplacements() {
			return [
				'/[^-\p{L}\p{N}\s]/u'=>'',
				'/^[-]+/'=>'',
				'/[-]+$/'=>'',
				'/[-]+/'=>'-',
			];
		}
		
		public function GenerateURLCodeFromValues_UserSubmission($args) {
			$code = $args['code'];
			
			if(!$this->isUserAdmin()) {
				if($this->handler->desired_action === 'Edit' && $this->entry['Publish'] !== 1) {
					# no code changes
				} else {
					$user_id = $this->getUserId();
					$code .= '-';
					if($this->authentication_object->user_session['User.id']) {
						$code .= $this->authentication_object->user_session['User.id'];
					} else {
						$code .= '0';
					}
					
					$code .= '-' . time();
				}
			}
			
			return $code;
		}
	}

	trait UnifiedGenerator {
		use URLCodeGenerator;
		use ListTitleGenerator;
		use ListTitleKeyGenerator;
		public function GenerateAllValuesForEntry($args) {
			$entry = $args['entry'];
			
			return [
				'url'=>$this->GenerateURLCodeFromValues([
					'values'=>[
						$entry['Title'],
						$entry['Subtitle'],
						$entry['Description'],
						implode(' ', $entry['tags']),
					],
				]),
				'ListTitle'=>$this->GenerateEntryListTitle(['title'=>$entry['Title'] . ': ' . $entry['Subtitle']]),
				'ListTitleSortKey'=>$this->GenerateEntryListTitleSortKey(['title'=>$entry['Title'] . ': ' . $entry['Subtitle']]),
			];
		}
	}
	
	class TitleKeyURLGenerator {
		use UnifiedGenerator;
	}
	
?>
