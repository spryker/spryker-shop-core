import Component from 'ShopUi/models/component';
import TraceableEventsOrchestrator, {
    EventName,
    EventsHandlerData,
    TraceableEventHandler,
} from './traceable-events-orchestrator';

export type TraceableEventHandlers = Partial<Record<EventName, TraceableEventHandler[]>>;

export class BaseTraceableEventAdapter extends Component {
    protected orchestratorSelector: string;

    protected readyCallback(): void {}
    protected init(): void {
        this.addAdapter();
    }

    protected getHandlers(): Partial<TraceableEventHandlers> {
        throw new Error('BaseTraceableEventAdapter: getHandlers - Method is not implemented.');
    }

    addAdapter(orchestratorSelector = '.js-traceable-events-orchestrator'): void {
        document.querySelector<TraceableEventsOrchestrator>(orchestratorSelector)?.addAdapter(this);
    }

    async eventsHandler(data: EventsHandlerData<EventName>): Promise<void> {
        await Promise.all(this.getHandlers()[data.event].map((handler) => handler.apply(this, [data])));
    }

    disconnectedCallback() {
        document.querySelector<TraceableEventsOrchestrator>(this.orchestratorSelector)?.removeAdapter(this);
    }
}
