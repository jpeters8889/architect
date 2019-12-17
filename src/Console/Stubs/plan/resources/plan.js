import PlanList from "./Components/PlanList";
import PlanForm from "./Components/PlanForm";

Architect.onBoot((Vue) => {
   Vue.component('{{package-name}}-list', PlanList);
   Vue.component('{{package-name}}-form', PlanForm);
});
