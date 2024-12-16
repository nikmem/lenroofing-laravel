<?php

// Constants
use App\Constants\General;

// Carbon
use Carbon\Carbon;

/**
 * Carbon parse specific value.
 *
 * @param  string value
 * @param  string format (optional)
 * @return string|object
 */
function carbonDate($value, $format = null)
{
	$dateTime = Carbon::parse($value);

	if (!is_null($format))
		$dateTime = $dateTime->format($format);

	return $dateTime;
}

function carbonIsBefore($first, $second)
{
	return Carbon::parse($first)->isBefore(Carbon::parse($second));
}

function carbonDateUK($value, $format = 'd/m/Y H:i')
{
	return Carbon::parse($value)->format($format);
}

/**
 * Carbon parse current date time.
 *
 * @param  string format (optional)
 * @return string|object
 */
function currentCarbonDate($format = null)
{
	$dateTime = Carbon::now();

	if (!is_null($format))
		$dateTime = $dateTime->format($format);

	return $dateTime;
}

/**
 * Generate Date Range.
 *
 * @param  string date
 * @param  string filter
 * @return array
 */
function getDateRange($date, $filter)
{
	$SD = $ED = "";
	$format = General::DATE_FORMAT_1;
	$date = carbonDate($date);

	if ($filter == General::FILTER_WEEK) {
		$SD = carbonDate($date->startOfWeek(), $format);
		$ED = carbonDate($date->endOfWeek(), $format);
	} elseif ($filter == General::FILTER_MONTH) {
		$SD = carbonDate($date->startOfMonth(), $format);
		$ED = carbonDate($date->endOfMonth(), $format);
	} elseif ($filter == General::FILTER_YEAR) {
		$SD = carbonDate($date->startOfYear(), $format);
		$ED = carbonDate($date->endOfYear(), $format);
	}
	return [$SD, $ED];
}