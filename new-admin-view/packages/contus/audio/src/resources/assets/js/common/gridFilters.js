window.gridFilters = {INR : function () {
    return function (input) {
        if (! isNaN(input)) {
            var currencySymbol = '';
            //var output = Number(input).toLocaleString('en-IN');   <-- This method is not working fine in all browsers!           
            var result = input.toString().split('.');

            var lastThree = result[0].substring(result[0].length - 3);
            var otherNumbers = result[0].substring(0, result[0].length - 3);
            if (otherNumbers != '')
                lastThree = ',' + lastThree;
            var output = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
            
            if (result.length > 1) {
                output += "." + result[1];
            }            

            return currencySymbol + output;
        }
    }
},
	/* Use this $filter to round Numbers UP, DOWN and to his nearest neighbour.
		You can also use multiples */
	/* Usage Examples:
	    - Round Nearest: {{ 4.4 | round }} // result is 4
	    - Round Up: {{ 4.4 | round:'':'up' }} // result is 5
	    - Round Down: {{ 4.6 | round:'':'down' }} // result is 4
	    ** Multiples
	    - Round by multiples of 10 {{ 5 | round:10 }} // result is 10
	    - Round UP by multiples of 10 {{ 4 | round:10:'up' }} // result is 10
	    - Round DOWN by multiples of 10 {{ 6 | round:10:'down' }} // result is 0
	*/
	roundNumber : function(){
		return function (value, mult, dir) {
			dir = dir || 'nearest';
			mult = mult || 1;
			value = !value ? 0 : Number(value);
			if (dir === 'up') {
				return Math.ceil(value / mult) * mult;
			} else if (dir === 'down') {
				return Math.floor(value / mult) * mult;
			} else {
				return Math.round(value / mult) * mult;
			}
		};
	}
};