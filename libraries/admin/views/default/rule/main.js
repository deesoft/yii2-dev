module.factory('Rule', ['Rest', function (Rest) {
        return Rest('rule/:id');
    }]);

ResolveRuleQuery.$inject = ['Rule'];
function ResolveRuleQuery(Rule) {
    return Rule.query();
}
