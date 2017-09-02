<template>
  <b-field :label="label" :type="type" :message="message">
    <b-field expanded :type="type">
      <b-input :placeholder="placeholders.year"
               :value="year"
               @input="onYearInput"
               expanded>
      </b-input>
      <b-select :placeholder="placeholders.month"
                :value="month"
                @input="onMonthInput">
        <option :value="null"></option>
        <option v-for="(month, index) in months" :value="(index + 1)" v-text="month"></option>
      </b-select>
      <b-select :placeholder="placeholders.day"
                :value="day"
                @input="onDayInput">
        <option :value="null"></option>
        <option v-for="day in days" :value="day" v-text="day"></option>
      </b-select>
    </b-field>
  </b-field>
</template>

<script>
import moment from 'moment';
import { range } from 'lodash-es';

export default {
  name: 'nz-date-input',

  props: {
    label: {
      type: String,
      default: 'Date'
    },

    dataYear: {
      type: Number,
      default: moment().year()
    },

    dataMonth: {
      type: Number,
      default: moment().month() + 1
    },

    dataDay: {
      type: Number,
      default: moment().date()
    },

    placeholders: {
      type: Object,
      default() {
        return {
          year: 'Year',
          month: 'Month',
          day: 'Day'
        };
      }
    },

    type: {
      type: String,
      default: ''
    },
    message: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      year: this.dataYear,
      month: this.dataMonth,
      day: this.dataDay,
    }
  },

  computed: {
    now() {
      return moment();
    },

    months() {
      if (!this.year || this.year > moment().year()) return [];

      const isThisYear = this.year === moment().year();

      let months = moment.months();

      if (isThisYear) months.splice(moment().month() + 1);

      return months;
    },

    days() {
      const haveDate = this.year && this.month;

      if (!haveDate) return [];

      const isThisMonth = this.year === moment().year() && (this.month - 1) === moment().month();

      let days = moment({
        year: this.year,
        month: this.month - 1,
      }).daysInMonth();

      if (isThisMonth) days = moment().date();

      return range(1, days + 1);
    }
  },

  watch: {
    year() {
      if (this.year > moment().year() || this.months.length < this.month -1)  {
          this.month = null;
      }

      this.truncateDay();
    },

    month() {
      this.truncateDay();
    }
  },

  methods: {
    truncateDay() {
      if (this.days.length < this.day)  {
          this.day = null;
      }
    },

    onYearInput(value) {
      this.year = +value || null;

      this.$emit('yearInput', this.year);
    },

    onMonthInput(value) {
      this.month = value;

      this.$emit('monthInput', this.month);
    },

    onDayInput(value) {
      this.day = value;

      this.$emit('dayInput', this.day);
    }
  }
}
</script>
