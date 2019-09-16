import dayjs from 'dayjs'
import 'dayjs/locale/sr'
import 'dayjs/locale/sr-cyrl'
import 'dayjs/locale/hr'
import customParseFormat from 'dayjs/plugin/customParseFormat'
import _range from 'lodash/range'

dayjs.extend(customParseFormat)

let dayjsLocale = document.documentElement.lang

if (dayjsLocale === 'sr') {
  dayjsLocale = 'sr-cyrl'
} else if (dayjsLocale === 'sr-Latn') {
  dayjsLocale = 'sr'
}

dayjs.locale(dayjsLocale)

dayjs.prototype.isLeapYear = function () {
  return ((this.year() % 4 == 0) && (this.year() % 100 != 0)) || (this.year() % 400 == 0)
}

dayjs.prototype.dayOfYear = function () {
  const dayCount = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
  const month = this.month();
  const date = this.date();

  const dayOfYear = dayCount[month] + date;

  if (month > 1 && this.isLeapYear()) return dayOfYear + 1;

  return dayOfYear;
}

dayjs.months = function () {
  const year = dayjs().year()
  return _range(0, 12).map(month => dayjs(new Date(year, month, 1)).format('MMMM'))
}

export default dayjs
