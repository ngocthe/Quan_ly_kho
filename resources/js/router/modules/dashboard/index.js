import systemRouter from "./modules/system";
import businessRouter from "./modules/business";

const dashboardRouter = [...systemRouter, ...businessRouter];

export default dashboardRouter;
