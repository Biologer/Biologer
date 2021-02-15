<template>
  <b-field :label="label">
    <b-field expanded grouped>
      <b-field
        :type="errors.has('year') ? 'is-danger': ''"
        :message="errors.has('year') ? errors.first('year'): ''"
        expanded
      >
        <b-input :placeholder="placeholders.year" :value="year" @input="onYearInput" />
      </b-field>

      <b-field
        :type="errors.has('month') ? 'is-danger': ''"
        :message="errors.has('month') ? errors.first('month'): ''"
        expanded
      >
        <b-select :placeholder="placeholders.month" :value="month" @input="onMonthInput" expanded>
          <option :value="null"></option>
          <option v-for="(month, index) in months" :value="(index + 1)" v-text="month" :key="`month-select-${index}`"></option>
        </b-select>
      </b-field>

      <b-field
        :type="errors.has('day') ? 'is-danger': ''"
        :message="errors.has('day') ? errors.first('day'): ''"
        expanded
      >
        <b-select :placeholder="placeholders.day" :value="day" @input="onDayInput" expanded>
          <option :value="null"></option>
          <option v-for="day in days" :value="day" v-text="day" :key="`day-select-${day}`"></option>
        </b-select>
      </b-field>
    </b-field>
  </b-field>
</template>

<script>
import dayjs from '@/dayjs'
import _range from 'lodash/range'

export default {
  name: 'nzDateInput',

  props: {
    label: {
      type: String,
      default: 'Date'
    },

    year: {
      type: Number,
      default: dayjs().year()
    },

    month: {
      type: Number,
      default: dayjs().month() + 1
    },

    day: {
      type: Number,
      default: dayjs().date()
    },

    placeholders: {
      type: Object,
      default() {
        return {
          year: 'Year',
          month: 'Month',
          day: 'Day'
        }
      }
    },

    errors: {
      type: Object,
      default: () => ({ has: () => false })
    }
  },

  computed: {
    now() {
      return dayjs()
    },

    months() {
      if (!this.year || this.year > dayjs().year()) return []

      const isThisYear = this.year === dayjs().year()

      let months = dayjs.months()

      if (isThisYear) months.splice(dayjs().month() + 1)

      return months
    },

    days() {
      const haveDate = this.year && this.month

      if (!haveDate) return []

      const isThisMonth = this.year === dayjs().year() && (this.month - 1) === dayjs().month()

      let days = dayjs(new Date(this.year, this.month - 1, 1)).daysInMonth()

      if (isThisMonth) days = dayjs().date()

      return _range(1, days + 1)
    }
  },

  watch: {
    year() {
      if (this.year > dayjs().year() || this.months.length < this.month -1)  {
          this.onMonthInput(null)
      }

      this.truncateDay()
    },

    month() {
      this.truncateDay()
    }
  },

  methods: {
    truncateDay() {
      if (this.days.length < this.day)  {
          this.onDayInput(null)
      }
    },

    onYearInput(value) {
      this.$emit('update:year', +value || null)
    },

    onMonthInput(value) {
      this.$emit('update:month', value)
    },

    onDayInput(value) {
      this.$emit('update:day', value)
    }
  }
}
</script>
