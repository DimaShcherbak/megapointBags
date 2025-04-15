define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'underscore',
    'mage/template',
    'jquery/ui'
], function ($,utils) {

    return function (widget) {

        $.widget('mage.priceOptions', widget, {

            _onOptionChanged: function (event,widget) {
                var option = $(event.target);
                console.log("This was called instead of the parent _onOptionChanged function");
                this._super(event);
                var optionValue = option.val(),
                    optionId = utils.findOptionId(option[0]);
                var opt_weight=0, optionConfig=this.options.optionConfig[optionId];
                jQuery('#options-'+optionId+'-list input:checked').each(function(key,obj){
                    var opt=jQuery(obj);
                    if( optionConfig[opt.val()].weight>0){
                        opt_weight+=parseInt(optionConfig[opt.val()].weight);
                    }
                });
                if(opt_weight) {
                    jQuery('.AdditionalWeight').html('+'+opt_weight);
                }else{
                    jQuery('.AdditionalWeight').html('');
                }
                //this.options.optionConfig[optionId][optionValue].weight
                  //  console.log(this.options.optionConfig[optionId].weight)
            }


        });
        return $.mage.priceOptions;
    }
});
