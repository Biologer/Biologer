<template>
  <div class="datepicker control" :class="[size, {'is-expanded': expanded}]">
    <b-dropdown
      v-if="!isMobile || inline"
      ref="dropdown"
      :position="position"
      :disabled="disabled"
      :inline="inline"
    >
      <b-input
        v-if="!inline"
        ref="input"
        slot="trigger"
        autocomplete="off"
        :value="formatValue(dateSelected)"
        :placeholder="placeholder"
        :size="size"
        :icon="icon"
        :icon-pack="iconPack"
        :rounded="rounded"
        :loading="loading"
        :disabled="disabled"
        :readonly="readonly"
        v-bind="$attrs"
        @change.native="onChange($event.target.value)"
        @focus="$emit('focus', $event)"
        @blur="$emit('blur', $event) && checkHtml5Validity()
      />

      <b-dropdown-item :disabled="disabled" custom>
          <header class="datepicker-header">
            <template v-if="$slots.header !== undefined && $slots.header.length">
              <slot name="header" />
            </template>

            <div v-else class="pagination field is-centered">

            <a
              v-if="!isFirstMonth && !disabled"
              class="pagination-previous"
              role="button"
              href="#"
              :disabled="disabled"
              @click.prevent="decrementMonth"
              @keydown.enter.prevent="decrementMonth"
              @keydown.space.prevent="decrementMonth"
            >
              <b-icon
                icon="chevron-left"
                :pack="iconPack"
                both
                type="is-primary is-clickable"
              />
            </a>

            <a
              v-show="!isLastMonth && !disabled"
              class="pagination-next"
              role="button"
              href="#"
              :disabled="disabled"
              @click.prevent="incrementMonth"
              @keydown.enter.prevent="incrementMonth"
              @keydown.space.prevent="incrementMonth"
            >
              <b-icon
                icon="chevron-right"
                :pack="iconPack"
                both
                type="is-primary is-clickable"
              />
            </a>

            <div class="pagination-list">
              <b-field>
                <b-select
                  v-model="focusedDateData.month"
                  :disabled="disabled"
                >
                  <option
                    v-for="(month, index) in monthNames"
                    :value="index"
                    :key="month"
                  >
                    {{ month }}
                  </option>
                </b-select>

                <b-select
                  v-model="focusedDateData.year"
                  :disabled="disabled"
                >
                  <option
                    v-for="year in listOfYears"
                    :value="year"
                    :key="year"
                  >
                    {{ year }}
                  </option>
                </b-select>
              </b-field>
            </div>
          </div>
        </header>

        <b-datepicker-table
          :value="dateSelected"
          @input="onDateInput"
          :day-names="dayNames"
          :month-names="monthNames"
          :first-day-of-week="firstDayOfWeek"
          :min-date="minDate"
          :max-date="maxDate"
          :focused="focusedDateData"
          :disabled="disabled"
          :unselectable-dates="unselectableDates"
          :unselectable-days-of-week="unselectableDaysOfWeek"
          :selectable-dates="selectableDates"
          :events="events"
          :indicators="indicators"
          @close="$refs.dropdown.isActive = true"
        />

        <b-field expanded>
          <b-select
            v-model="hoursSelected"
            @change.native="onHoursChange($event.target.value)"
            :disabled="disabled"
            placeholder="00"
            expanded
          >
            <option
              v-for="hour in hours"
              :value="hour.value"
              :key="hour.value"
              :disabled="isHourDisabled(hour.value)"
            >
              {{ hour.label }}
            </option>
          </b-select>

          <b-select
            v-model="minutesSelected"
            @change.native="onMinutesChange($event.target.value)"
            :disabled="disabled"
            placeholder="00"
            expanded
          >
            <option
              v-for="minute in minutes"
              :value="minute.value"
              :key="minute.value"
              :disabled="isMinuteDisabled(minute.value)"
            >
              {{ minute.label }}
            </option>
          </b-select>

          <b-select
            v-model="meridienSelected"
            @change.native="onMeridienChange($event.target.value)"
            v-if="!isHourFormat24"
            :disabled="disabled"
          >
            <option
              v-for="meridien in meridiens"
              :value="meridien"
              :key="meridien"
            >
              {{ meridien }}
            </option>
          </b-select>
        </b-field>

        <footer
          v-if="$slots.default !== undefined && $slots.default.length"
          class="datepicker-footer"
        >
          <slot/>
        </footer>
      </b-dropdown-item>
    </b-dropdown>

    <b-input
      v-else
      ref="input"
      type="datetime"
      autocomplete="off"
      :value="formatYYYYMMDD(value)"
      :placeholder="placeholder"
      :size="size"
      :icon="icon"
      :icon-pack="iconPack"
      :loading="loading"
      :max="formatYYYYMMDD(maxDate)"
      :min="formatYYYYMMDD(minDate)"
      :disabled="disabled"
      :readonly="false"
      v-bind="$attrs"
      @change.native="onChangeNativePicker"
      @focus="$emit('focus', $event)"
      @blur="$emit('blur', $event) && checkHtml5Validity()"
    />
  </div>
</template>

<script>
import FormElementMixin from 'buefy/src/utils/FormElementMixin'
import { isMobile } from 'buefy/src/utils/helpers'
import config from 'buefy/src/utils/config'

import DatepickerTable from 'buefy/src/components/datepicker/DatepickerTable'

const AM = 'AM'
const PM = 'PM'
const HOUR_FORMAT_24 = '24'
const HOUR_FORMAT_12 = '12'

const formatNumber = (value) => (value < 10 ? '0' : '') + value

export default {
  name: 'nzDatetimePicker',

  components: {
    [DatepickerTable.name]: DatepickerTable,
  },

  mixins: [FormElementMixin],

  inheritAttrs: false,

  props: {
    value: Date,
    dayNames: {
      type: Array,
      default: () => {
        if (Array.isArray(config.defaultDayNames)) {
          return config.defaultDayNames
        }

        return moment.weekdaysShort()
      }
    },
    monthNames: {
      type: Array,
      default: () => {
        if (Array.isArray(config.defaultMonthNames)) {
          return config.defaultMonthNames
        }

        return moment.months()
      }
    },
    firstDayOfWeek: {
      type: Number,
      default: () => {
        if (typeof config.defaultFirstDayOfWeek === 'number') {
          return config.defaultFirstDayOfWeek
        }

        return 0
      }
    },
    inline: Boolean,
    minDate: Date,
    maxDate: Date,
    focusedDate: Date,
    placeholder: String,
    readonly: {
      type: Boolean,
      default: true
    },
    disabled: {
      type: Boolean,
      default: false
    },
    unselectableDates: Array,
    unselectableDaysOfWeek: {
      type: Array,
      default: () => config.defaultUnselectableDaysOfWeek
    },
    selectableDates: Array,
    displayFormat: {
      type: String,
      default: 'MM/DD/YYYY HH:mm'
    },
    dateFormatter: {
      type: Function,
      default: (date) => {
        if (typeof config.defaultDateFormatter === 'function') {
          return config.defaultDateFormatter(date)
        }

        return moment(date).format('MM/DD/YYYY HH:mm')
      }
    },
    dateParser: {
      type: Function,
      default: (date) => {
        if (typeof config.defaultDateParser === 'function') {
          return config.defaultDateParser(date)
        }

        return new Date(Date.parse(date))
      }
    },
    mobileNative: {
      type: Boolean,
      default: () => config.defaultDatepickerMobileNative
    },
    position: String,
    events: Array,
    indicators: {
      type: String,
      default: 'dots'
    },
    hourFormat: {
      type: String,
      default: HOUR_FORMAT_24,
      validator: value => value === HOUR_FORMAT_24 || value === HOUR_FORMAT_12
    },
    incrementMinutes: {
      type: Number,
      default: 1
    }
  },

  data() {
    const focusedDate = this.value || this.focusedDate || new Date()

    return {
      dateSelected: this.value,
      focusedDateData: {
        month: focusedDate.getMonth(),
        year: focusedDate.getFullYear()
      },
      hoursSelected: null,
      minutesSelected: null,
      meridienSelected: null,
      _elementRef: 'input',
      _isDatepicker: true,
    }
  },

  computed: {
    /*
    * Returns an array of years for the year dropdown. If earliest/latest
    * dates are set by props, range of years will fall within those dates.
    */
    listOfYears() {
      const latestYear = this.maxDate
        ? this.maxDate.getFullYear()
        : (Math.max(new Date().getFullYear(), this.focusedDateData.year) + 3)

      const earliestYear = this.minDate
        ? this.minDate.getFullYear()
        : 1900

      const arrayOfYears = []
      for (let i = earliestYear; i <= latestYear; i++) {
          arrayOfYears.push(i)
      }

      return arrayOfYears.reverse()
    },

    isFirstMonth() {
      if (!this.minDate) return false

      const dateToCheck = new Date(this.focusedDateData.year, this.focusedDateData.month)
      const date = new Date(this.minDate.getFullYear(), this.minDate.getMonth())

      return dateToCheck <= date
    },

    isLastMonth() {
      if (!this.maxDate) return false

      const dateToCheck = new Date(this.focusedDateData.year, this.focusedDateData.month)
      const date = new Date(this.maxDate.getFullYear(), this.maxDate.getMonth())

      return dateToCheck >= date
    },

    isMobile() {
      return this.mobileNative && isMobile.any()
    },

    hours() {
      const hours = []
      const numberOfHours = this.isHourFormat24 ? 24 : 12
      for (let i = 0; i < numberOfHours; i++) {
        let value = i
        let label = value
        if (!this.isHourFormat24) {
          value = (i + 1)
          label = value
          if (this.meridienSelected === AM) {
            if (value === 12) {
                value = 0
            }
          } else if (this.meridienSelected === PM) {
            if (value !== 12) {
                value += 12
            }
          }
        }
        hours.push({
          label: formatNumber(label),
          value: value
        })
      }

      return hours
    },

    minutes() {
      const minutes = []
      for (let i = 0; i < 60; i += this.incrementMinutes) {
          minutes.push({
              label: formatNumber(i),
              value: i
          })
      }

      return minutes
    },

    meridiens() {
      return [AM, PM]
    },

    isHourFormat24() {
      return this.hourFormat === HOUR_FORMAT_24
    }
  },

  watch: {
    /*
    * Emit input event with selected date as payload, set isActive to false.
    * Update internal focusedDateData
    */
    dateSelected(value) {
      const currentDate = !value ? new Date() : value
      this.focusedDateData = {
        month: currentDate.getMonth(),
        year: currentDate.getFullYear()
      }
      this.$emit('input', value)
      if (this.$refs.dropdown) {
        this.$refs.dropdown.isActive = true
      }
    },

    /**
     * When v-model is changed:
     *   1. Update internal value.
     *   2. If it's invalid, validate again.
     */
    value(value) {
      this.dateSelected = value

      !this.isValid && this.$refs.input.checkHtml5Validity()
    },

    focusedDate(value) {
      if (value) {
        this.focusedDateData = {
          month: value.getMonth(),
          year: value.getFullYear()
        }
      }
    },

    /*
    * Emit input event on month and/or year change
    */
    'focusedDateData.month'(value) {
      this.$emit('change-month', value)
    },
    'focusedDateData.year'(value) {
      this.$emit('change-year', value)
    },
    hourFormat(value) {
      if (this.hoursSelected !== null) {
        this.meridienSelected = this.hoursSelected >= 12 ? PM : AM
      }
    }
  },
  methods: {
    /*
    * Emit input event with selected date as payload for v-model in parent
    */
    updateSelectedDate(date) {
      this.dateSelected = date
    },

    /*
    * Parse string into date
    */
    onChange(value) {
      const date = this.dateParser(value)
      if (date && !isNaN(date)) {
        this.dateSelected = date
      } else {
        // Force refresh input value when not valid date
        this.dateSelected = null
        this.$refs.input.newValue = this.dateSelected
      }
    },

    /*
    * Format date into string
    */
    formatValue(value) {
      if (value && !isNaN(value)) {
        return this.dateFormatter(value)
      }
    },

    /*
    * Either decrement month by 1 if not January or decrement year by 1
    * and set month to 11 (December)
    */
    decrementMonth() {
      if (this.disabled) return

      if (this.focusedDateData.month > 0) {
        this.focusedDateData.month -= 1
      } else {
        this.focusedDateData.month = 11
        this.focusedDateData.year -= 1
      }
    },

      /*
      * Either increment month by 1 if not December or increment year by 1
      * and set month to 0 (January)
      */
      incrementMonth() {
        if (this.disabled) return

        if (this.focusedDateData.month < 11) {
          this.focusedDateData.month += 1
        } else {
          this.focusedDateData.month = 0
          this.focusedDateData.year += 1
        }
      },

      /*
      * Format date into string 'YYYY-MM-DD'
      */
      formatYYYYMMDD(value) {
        const date = new Date(value)

        if (value && !isNaN(date)) {
          const year = date.getFullYear()
          const month = date.getMonth() + 1
          const day = date.getDate()

          return year + '-' +
            ((month < 10 ? '0' : '') + month) + '-' +
            ((day < 10 ? '0' : '') + day)
        }

        return ''
      },

      onDateInput(value) {
        if (this.dateSelected) {
          value.setHours(this.dateSelected.getHours())
          value.setMinutes(this.dateSelected.getMinutes())
        }

        this.dateSelected = value
      },

      /*
      * Parse date from string
      */
      onChangeNativePicker(event) {
        const date = event.target.value
        this.dateSelected = date ? new Date(date) : null
      },

      onMeridienChange(value) {
        if (this.hoursSelected !== null) {
          if (value === PM) {
            if (this.hoursSelected === 0) {
              this.hoursSelected = 12
            } else {
              this.hoursSelected += 12
            }
          } else if (value === AM) {
            if (this.hoursSelected === 12) {
              this.hoursSelected = 0
            } else {
              this.hoursSelected -= 12
            }
          }
        }
        this.updateDateSelected(this.hoursSelected, this.minutesSelected, value)
      },

      onHoursChange(value) {
        this.updateDateSelected(
          parseInt(value, 10),
          this.minutesSelected,
          this.meridienSelected
        )
      },

      onMinutesChange(value) {
        this.updateDateSelected(
          this.hoursSelected,
          parseInt(value, 10),
          this.meridienSelected
        )
      },

      updateDateSelected(hours, minutes, meridiens) {
        if (hours != null && minutes != null &&
          ((!this.isHourFormat24 && meridiens !== null) || this.isHourFormat24)) {
          if (this.dateSelected && !isNaN(this.dateSelected)) {
            this.dateSelected = new Date(this.dateSelected)
          } else {
            this.dateSelected = new Date()
            this.dateSelected.setMilliseconds(0)
            this.dateSelected.setSeconds(0)
          }
          this.dateSelected.setHours(hours)
          this.dateSelected.setMinutes(minutes)
        }
      },

      isHourDisabled(hour) {
        let disabled = false
        if (this.minTime) {
          const minHours = this.minTime.getHours()
          disabled = hour < minHours
        }
        if (this.maxTime && !disabled) {
          const maxHours = this.maxTime.getHours()
          disabled = hour > maxHours
        }
        if (this.unselectableTimes && !disabled) {
          if (this.minutesSelected !== null) {
            const unselectable = this.unselectableTimes.filter(
              time => time.getHours() === hour && time.getMinutes() === this.minutesSelected
            )
            disabled = unselectable.length > 0
          } else {
            const unselectable = this.unselectableTimes.filter(time => time.getHours() === hour)
            disabled = unselectable.length === this.minutes.length
          }
        }

        return disabled
      },

    isMinuteDisabled(minute) {
      let disabled = false
      if (this.hoursSelected !== null) {
        if (this.isHourDisabled(this.hoursSelected)) {
            disabled = true
        } else if (this.minTime) {
          const minHours = this.minTime.getHours()
          const minMinutes = this.minTime.getMinutes()
          disabled = this.hoursSelected === minHours && minute < minMinutes
        } else if (this.maxTime && !disabled) {
          const maxHours = this.maxTime.getHours()
          const minMinutes = this.maxTime.getMinutes()
          disabled = this.hoursSelected === maxHours && minute > minMinutes
        }
        if (this.unselectableTimes && !disabled) {
          const unselectable = this.unselectableTimes.filter((time) => {
            return time.getHours() === this.hoursSelected &&
              time.getMinutes() === minute
          })
          disabled = unselectable.length > 0
        }
      }

      return disabled
    }
  }
}
</script>
