import * as yup from "yup"

/**
 * Validation rule for max numbers after dot
 * @param  max
 * @returns  {NumberSchema<NonNullable<number | undefined>, AnyObject, undefined, "">}
 */
export const maxDecimalPlaces = (max) => {
  return yup
    .number()
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