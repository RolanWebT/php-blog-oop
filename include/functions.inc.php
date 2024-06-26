<?php
#**********************************************************************************#


				#******************************#
				#********** FUNCTIONS INC **********#
				#******************************#


#**********************************************************************************#


				#*************************************#
				#********** IN MULT ARRAY FUNC **********#
				#*************************************#
				
				/**
				*
				*	Ersetzt potentiell gefährliche Steuerzeichen durch HTML-Entities
				*	Entfernt vor und nach einem String unnötige Whitespaces
				*	Ersetzt Leerstring und reine Whitespaces durch NULL
				*	
				*	@params		String	$value								Die zu bereinigende Zeichenkette
				*	@params		Bool		$convertEmptyStringToNull		Angabe, ob Leerstrings in NULL umgewandelt werden sollen
				*
				*	@return		String|NULL			Die bereinigte Zeichenkette|NULL, falls $value NULL oder '' ist
				*
				*/
				function in_mult_array($value, $array) {
					#********** LOCAL SCOPE START **********#
if(DEBUG_F)		echo "<p class='debug in_mult_array'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value', '$array') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
				return true;
					#********** LOCAL SCOPE END **********#
				}


#**********************************************************************************#
?>