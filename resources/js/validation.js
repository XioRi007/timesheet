import * as yup from "yup"

export const maxDecimalPlaces = (max) => {
  return yup
    .number()
    .required()
    .test({
      name: 'decimal-places',
      params: {max},
      message: '${path} should have at most ${max} decimal places',
      test: (value) => {
        if (value === undefined || value === null) {
          return true
        }
        const decimalPlaces = value.toString().split('.')[1]?.length || 0
        return decimalPlaces <= max
      }
    })
}